import firebase_admin
from firebase_admin import credentials
from firebase_admin import firestore
import mysql.connector
from mysql.connector import Error
import os
from dotenv import load_dotenv
import json

# Charger les variables d'environnement
load_dotenv()

def init_firebase():
    """Initialise la connexion Firebase"""
    cred = credentials.Certificate('firebase-credentials.json')
    firebase_admin.initialize_app(cred)
    return firestore.client()

def connect_mysql():
    """Connexion à la base MySQL"""
    try:
        connection = mysql.connector.connect(
            host=os.getenv('MYSQL_HOST', 'localhost'),
            database=os.getenv('MYSQL_DATABASE', 'decorezr_mysql'),
            user=os.getenv('MYSQL_USER', 'root'),
            password=os.getenv('MYSQL_PASSWORD', 'root'),
            port=8889  # Port MAMP par défaut
        )
        return connection
    except Error as e:
        print(f"Erreur de connexion MySQL: {e}")
        return None

def migrate_members(mysql_cursor, db):
    """Migration de la table member"""
    mysql_cursor.execute("SELECT * FROM member")
    members = mysql_cursor.fetchall()
    
    for member in members:
        member_doc = {
            'email': member['email'],
            'password': member['password'],  # À hasher correctement
            'firstName': member['firstname'],
            'lastName': member['lastname'],
            'phone': member['phone'],
            'isActive': bool(member['is_active']),
            'role': member['role'],
            'companyName': member['company_name'],
            'siret': member['siret'],
            'facebookId': str(member['facebook_id']) if member['facebook_id'] else None,
            'departmentId': str(member['department_id']) if member['department_id'] else None,
            'createdAt': member['created_at'].isoformat(),
            'updatedAt': member['updated_at'].isoformat(),
        }
        db.collection('members').document(str(member['id'])).set(member_doc)

def migrate_announcements(mysql_cursor, db):
    """Migration de la table announcement"""
    mysql_cursor.execute("SELECT * FROM announcement")
    announcements = mysql_cursor.fetchall()
    
    for announcement in announcements:
        announcement_doc = {
            'memberId': str(announcement['member_id']),
            'departmentId': str(announcement['department_id']),
            'description': announcement['description'],
            'isVisible': bool(announcement['is_visible']),
            'urgentExpireAt': announcement['urgent_expire_at'].isoformat() if announcement['urgent_expire_at'] else None,
            'topListExpireAt': announcement['top_list_expire_at'].isoformat() if announcement['top_list_expire_at'] else None,
            'budget': announcement['budget'],
            'duration': announcement['duration'],
            'createdAt': announcement['created_at'].isoformat(),
            'updatedAt': announcement['updated_at'].isoformat(),
            'hasImage': bool(announcement['is_image'])
        }
        db.collection('announcements').document(str(announcement['id'])).set(announcement_doc)

def migrate_departments(mysql_cursor, db):
    """Migration de la table department"""
    mysql_cursor.execute("SELECT d.*, r.name as region_name FROM department d LEFT JOIN region r ON d.region_id = r.id")
    departments = mysql_cursor.fetchall()
    
    for department in departments:
        department_doc = {
            'name': department['name'],
            'regionId': str(department['region_id']) if department['region_id'] else None,
            'regionName': department['region_name'] if department['region_name'] else None
        }
        db.collection('departments').document(str(department['id'])).set(department_doc)

def main():
    # Initialiser Firebase
    db = init_firebase()
    
    # Connexion MySQL
    mysql_conn = connect_mysql()
    if not mysql_conn:
        return
    
    mysql_cursor = mysql_conn.cursor(dictionary=True)
    
    try:
        # Migration des données
        migrate_members(mysql_cursor, db)
        migrate_departments(mysql_cursor, db)
        migrate_announcements(mysql_cursor, db)
        
        print("Migration terminée avec succès!")
        
    except Exception as e:
        print(f"Erreur pendant la migration: {e}")
    
    finally:
        mysql_cursor.close()
        mysql_conn.close()

if __name__ == "__main__":
    main()
