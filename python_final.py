import sys
import joblib
import pandas as pd  # Import pandas

# Load the saved model
model = joblib.load('C:\\wamp64\\www\\review\\Geethu\\phishing_detection_model.pkl')

def predict_url(url):
    url_length = len(url)
    special_characters = sum(not c.isalnum() for c in url)
    uses_https = 1 if url.startswith('https') else 0

    # Create a DataFrame for the input features
    input_data = pd.DataFrame([[url_length, special_characters, uses_https]], 
                               columns=['url_length', 'special_characters', 'uses_https'])

    # Use the loaded model to make a prediction
    prediction = model.predict(input_data)

    # Return "Phishing" or "Legitimate" based on the prediction
    return 'Phishing' if prediction[0] == 1 else 'Legitimate'

if __name__ == "__main__":
    # Get the URL passed from the PHP script
    url = sys.argv[1]
    result = predict_url(url)
    print(result)  # Output result to be captured by PHP
