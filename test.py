import os

# Check if the file exists
file_path = 'C:\\wamp64\\www\\review\\Geethu\\phishing_model.pkl'
print(os.path.exists(file_path))  # This will print True if the file exists, False otherwise
import sys
import joblib

# Load the saved model
model = joblib.load('C:\\wamp64\\www\\review\\Geethu\\phishing_detection_model.pkl')  # Update the path accordingly

def predict_url(url):
    # Feature extraction
    url_length = len(url)
    special_characters = sum(not c.isalnum() for c in url)
    uses_https = 1 if url.startswith('https') else 0

    # Use the loaded model to make a prediction
    prediction = model.predict([[url_length, special_characters, uses_https]])

    # Return "Phishing" or "Legitimate" based on the prediction
    return 'Phishing' if prediction[0] == 1 else 'Legitimate'

if __name__ == "__main__":
    # Get the URL passed from the PHP script
    url = sys.argv[1]
    result = predict_url(url)
    print(result)  # Output result to be captured by PHP
