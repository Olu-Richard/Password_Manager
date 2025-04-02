// Log when content script is loaded
console.log('Password Manager content script loaded on:', window.location.href);

// Listen for messages from the popup
chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
    console.log('Received message:', request);
    
    if (request.action === "fillCredentials") {
        try {
            const { username, password } = request.credentials;
            console.log('Attempting to fill credentials for:', username);

            // Find all input fields immediately
            const allInputs = document.querySelectorAll('input');
            console.log('Found input fields:', allInputs.length);
            
            // Log all input fields for debugging
            allInputs.forEach((input, index) => {
                console.log(`Input field ${index}:`, {
                    type: input.type,
                    id: input.id,
                    name: input.name,
                    class: input.className,
                    placeholder: input.placeholder
                });
            });

            let usernameField = null;
            let passwordField = null;

            // First try to find fields by type
            passwordField = document.querySelector('input[type="password"]');
            usernameField = document.querySelector('input[type="email"]') || 
                           document.querySelector('input[type="text"]');

            // If not found, try other methods
            if (!usernameField || !passwordField) {
                allInputs.forEach(input => {
                    const inputText = (input.placeholder || input.name || input.id || '').toLowerCase();
                    const parentText = input.parentElement?.textContent?.toLowerCase() || '';
                    
                    if (!usernameField && (inputText.includes('email') || parentText.includes('email'))) {
                        usernameField = input;
                    }
                    if (!passwordField && input.type === 'password') {
                        passwordField = input;
                    }
                });
            }

            console.log('Found fields:', {
                usernameField: usernameField ? {
                    type: usernameField.type,
                    id: usernameField.id,
                    name: usernameField.name
                } : 'Not found',
                passwordField: passwordField ? {
                    type: passwordField.type,
                    id: passwordField.id,
                    name: passwordField.name
                } : 'Not found'
            });

            if (!usernameField || !passwordField) {
                console.log('Could not find all required fields');
                sendResponse({
                    success: false,
                    message: 'Could not find all required fields'
                });
                return true;
            }

            // Fill the fields
            try {
                // Fill username
                usernameField.focus();
                usernameField.value = username;
                usernameField.dispatchEvent(new Event('input', { bubbles: true }));
                usernameField.dispatchEvent(new Event('change', { bubbles: true }));
                
                // Fill password
                passwordField.focus();
                passwordField.value = password;
                passwordField.dispatchEvent(new Event('input', { bubbles: true }));
                passwordField.dispatchEvent(new Event('change', { bubbles: true }));

                console.log('Fields filled successfully');
                
                // Verify the values were set
                const success = usernameField.value === username && passwordField.value === password;
                sendResponse({
                    success: success,
                    message: success ? 'Fields filled successfully' : 'Values were not set correctly'
                });
            } catch (error) {
                console.error('Error filling fields:', error);
                sendResponse({
                    success: false,
                    message: 'Error filling fields: ' + error.message
                });
            }
        } catch (error) {
            console.error('Auto-fill error:', error);
            sendResponse({
                success: false,
                message: error.message
            });
        }
        return true;
    }
});

function isLoginField(input) {
    const loginKeywords = ['user', 'email', 'login', 'id', 'username'];
    const fieldName = (input.name || input.id || '').toLowerCase();
    return loginKeywords.some(keyword => fieldName.includes(keyword));
} 