import pandas as pd
import mysql.connector

# Step 1: Establish the database connection
conn = mysql.connector.connect(
    host='localhost',          # Replace with your database host
    user='root',      # Replace with your database username
    password='pass123',  # Replace with your database password
    database='phishing_detection'        # Replace with your database name
)

# Step 2: Create a cursor object
cursor = conn.cursor()

# Step 3: Execute the SQL query
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

# Step 8: Display the DataFrame
print(df)

# You can now proceed with further processing, like feature engineering, etc.
