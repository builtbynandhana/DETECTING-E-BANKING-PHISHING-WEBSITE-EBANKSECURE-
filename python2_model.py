import pandas as pd
import mysql.connector
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
from sklearn.metrics import classification_report, confusion_matrix

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
# Extract features
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
model = RandomForestClassifier(n_estimators=100, random_state=42)

# Train the model
model.fit(X_train, y_train)

# Step 11: Model Evaluation
# Make predictions on the test set
y_pred = model.predict(X_test)

# Print confusion matrix and classification report
print(confusion_matrix(y_test, y_pred))
print(classification_report(y_test, y_pred))
