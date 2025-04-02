// Background script for handling extension events
chrome.runtime.onInstalled.addListener(() => {
  console.log('Password Manager Extension installed');
}); 