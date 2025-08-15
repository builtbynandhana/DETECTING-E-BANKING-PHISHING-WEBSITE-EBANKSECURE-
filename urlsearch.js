// Get the form element
document.getElementById('phishing-form').addEventListener('submit', function (e) {
    const urlInput = document.getElementById('url').value;  // Get the input URL value
    const resultsSection = document.getElementById('results');  // Get the results section to display messages

    // Simple URL format validation
    if (!isValidURL(urlInput)) {
        e.preventDefault(); // Prevent form submission if the URL is not valid
        resultsSection.innerHTML = `<p style="color: red;">Please enter a valid URL.</p>`;  // Show error message
    } else {
        // Allow the form to submit as normal, letting PHP handle the backend
        resultsSection.innerHTML = `<p>Analyzing the URL: ${urlInput}...</p>`;  // Optional: show "analyzing" message
    }
});

// URL validation function
function isValidURL(string) {
    try {
        new URL(string);  // Try to create a URL object
        return true;  // If successful, the URL is valid
    } catch (_) {
        return false;  // If it fails, the URL is invalid
    }
}
