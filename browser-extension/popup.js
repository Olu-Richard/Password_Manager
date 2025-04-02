document.addEventListener('DOMContentLoaded', () => {
    const credentialsDiv = document.getElementById('credentials');
    
    // Get current tab URL
    chrome.tabs.query({active: true, currentWindow: true}, (tabs) => {
        if (!tabs[0]?.url) {
            credentialsDiv.innerHTML = '<p>Unable to get current tab URL</p>';
            return;
        }

        const currentUrl = new URL(tabs[0].url);
        const domain = currentUrl.hostname;
        
        console.log('Current URL:', currentUrl.href);
        console.log('Domain:', domain);
        
        // Show loading state
        credentialsDiv.innerHTML = '<p>Loading credentials...</p>';
        
        // Prepare request data
        const requestData = { domain: domain };
        console.log('Sending request with data:', requestData);
        
        // Fetch credentials for the current domain
        fetch('http://localhost/Richard_Olummanuel/Project_Manager/api/get_credentials.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(requestData),
            credentials: 'include'
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.text().then(text => {
                try {
                    // Try to parse as JSON
                    const data = JSON.parse(text);
                    console.log('Response data:', data);
                    if (!response.ok) {
                        throw new Error(data.message || `HTTP error! status: ${response.status}`);
                    }
                    return data;
                } catch (e) {
                    console.error('Failed to parse response:', text);
                    throw new Error('Invalid JSON response from server');
                }
            });
        })
        .then(data => {
            if (data.success && data.credentials && data.credentials.length > 0) {
                console.log('Found credentials:', data.credentials.length);
                credentialsDiv.innerHTML = '';
                data.credentials.forEach(cred => {
                    const entry = document.createElement('div');
                    entry.className = 'password-entry';
                    entry.innerHTML = `
                        <div>Website: ${cred.service}</div>
                        <div>Username: ${cred.username}</div>
                        <button class="auto-fill-btn" data-username="${cred.username}" data-password="${cred.password}">
                            Auto-fill
                        </button>
                        <div class="status-message"></div>
                    `;
                    
                    const button = entry.querySelector('.auto-fill-btn');
                    const statusDiv = entry.querySelector('.status-message');
                    
                    button.addEventListener('click', () => {
                        console.log('Auto-fill button clicked');
                        // Disable button and show loading state
                        button.disabled = true;
                        statusDiv.textContent = 'Filling credentials...';
                        statusDiv.className = 'status-message status-pending';
                        
                        chrome.tabs.sendMessage(
                            tabs[0].id,
                            {
                                action: "fillCredentials",
                                credentials: {
                                    username: cred.username,
                                    password: cred.password
                                }
                            },
                            (response) => {
                                console.log('Fill credentials response:', response);
                                if (chrome.runtime.lastError) {
                                    console.error('Chrome runtime error:', chrome.runtime.lastError);
                                    statusDiv.textContent = 'Error: Could not communicate with the page';
                                    statusDiv.className = 'status-message status-error';
                                    button.disabled = false;
                                    return;
                                }

                                if (response?.success) {
                                    statusDiv.textContent = 'Credentials filled successfully!';
                                    statusDiv.className = 'status-message status-success';
                                    setTimeout(() => window.close(), 1000);
                                } else {
                                    statusDiv.textContent = 'Failed to fill credentials. Please try manual input.';
                                    statusDiv.className = 'status-message status-error';
                                    button.disabled = false;
                                }
                            }
                        );
                    });
                    
                    credentialsDiv.appendChild(entry);
                });
            } else {
                console.log('No credentials found');
                credentialsDiv.innerHTML = '<p>No saved credentials for this website.</p>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            credentialsDiv.innerHTML = `
                <p>Error loading credentials. Please make sure:</p>
                <ul>
                    <li>XAMPP server is running</li>
                    <li>Database is properly configured</li>
                    <li>You are logged in to the password manager</li>
                </ul>
                <p>Error details: ${error.message}</p>
                <p>Check the console for more details.</p>
            `;
        });
    });
});

function fillCredentials(username, password, callback) {
    chrome.tabs.query({active: true, currentWindow: true}, (tabs) => {
        chrome.tabs.sendMessage(
            tabs[0].id,
            {
                action: "fillCredentials",
                credentials: { username, password }
            },
            (response) => {
                if (response?.success) {
                    callback(true, response.message);
                } else {
                    console.error('Fill credentials response:', response);
                    callback(false, response?.message || 'No response from content script');
                }
            }
        );
    });
} 