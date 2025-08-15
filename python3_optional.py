import pandas as pd
import mysql.connector
from sklearn.model_selection import train_test_split, GridSearchCV, cross_val_score
from sklearn.ensemble import RandomForestClassifier
from sklearn.metrics import classification_report, confusion_matrix
import matplotlib.pyplot as plt
from flask import Flask, request, jsonify

# Step 1: Establish the database connection
conn = mysql.connector.connect(
    host='localhost',          # Replace with your database host
    user='root',      # Replace with your database username
    password='pass123',  # Replace with your database password
    database='phishing_detection'        # Replace with your database name
)

# Step 2: Create a cursor object
cursor = conn.cursor()

# Step 3: Execute the SQL query to fetch data from the urls_table
cursor.execute("SELECT * FROM phishing;")  # Replace with your actual table name

# Step 4: Fetch all results
rows = cursor.fetchall()

# Step 5: Get column names
column_names = [i[0] for i in cursor.description]

# Step 6: Create a DataFrame
df = pd.DataFrame(rows, columns=column_names)

# Step 7: Close the cursor and connection
cursor.close()
conn.close()

# Step 8: Feature Engineering
df['url_length'] = df['url'].apply(len)
df['special_characters'] = df['url'].apply(lambda x: sum(not c.isalnum() for c in x))
df['uses_https'] = df['url'].apply(lambda x: 1 if x.startswith('https') else 0)

# Step 9: Prepare Data for Model Training
X = df[['url_length', 'special_characters', 'uses_https']]
y = df['if_phish']

# Split the data into training and testing sets
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Step 10: Model Selection and Training
# Create a Random Forest model instance
# Model Selection and Training
# Create a Random Forest model instance
model = RandomForestClassifier(n_estimators=100, random_state=42)

# Hyperparameter tuning with GridSearchCV
param_grid = {
    'n_estimators': [100, 200, 300],
    'max_features': ['sqrt', 'log2'],  # Removed 'auto'
    'max_depth': [None, 10, 20, 30],
    'min_samples_split': [2, 5, 10]
}

grid_search = GridSearchCV(estimator=model, param_grid=param_grid, cv=3, n_jobs=-1, verbose=2)
grid_search.fit(X_train, y_train)

# Best parameters found
best_model = grid_search.best_estimator_
print("Best parameters found: ", grid_search.best_params_)


# Step 11: Model Evaluation
# Make predictions on the test set
y_pred = best_model.predict(X_test)

# Print confusion matrix and classification report
print(confusion_matrix(y_test, y_pred))
print(classification_report(y_test, y_pred))

# Step 12: Feature Importance Analysis
feature_importances = best_model.feature_importances_
features = X.columns
plt.barh(features, feature_importances)
plt.xlabel('Feature Importance')
plt.title('Feature Importance for Phishing Detection Model')
plt.show()

# Step 13: Cross-validation
scores = cross_val_score(best_model, X, y, cv=5)
print("Cross-validation scores: ", scores)
print("Average score: ", scores.mean())

# Step 14: Model Deployment with Flask
app = Flask(__name__)

@app.route('/predict', methods=['POST'])
def predict():
    data = request.json
    url = data.get('url')
    
    # Feature extraction for the input URL
    url_length = len(url)
    special_characters = sum(not c.isalnum() for c in url)
    uses_https = 1 if url.startswith('https') else 0
    
    # Make a prediction
    prediction = best_model.predict([[url_length, special_characters, uses_https]])
    return jsonify({'is_phishing': prediction[0]})

if __name__ == '__main__':
    app.run(debug=True)
