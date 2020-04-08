# Simple API User

App with simple user register and login via browser. Also includes REST API for user managent.

### Usage via Browser

Open in browser **index.php** from **public** dir.

Endpoints:
- **/register** - allow register new user
- **/login** - login form


### REST API
##### Info
You need to send specific content type:
```
Content-type: application/x-www-form-urlencoded
```
All responses will be returned in **JSON** format.

##### Authorization
For authorization it is necessary to send the required headers:

| Key | Value |
| ------ | ------ |
| X-AUTH-USERNAME | User name |
| X-AUTH-TOKEN | User ApiToken |

##### User methods

| Endpoint | Method | Parameters | Description |
| ------ | ------ | ------ | ------ | 
| **/api/users/** | `GET` | ```null``` | get all users |
| **/api/users/{userId}** | `GET` | ```null``` | get single user data |
| **/api/users/** | `POST` | **username** - user name <br> **email** - user email <br> **plainPassword** - password | add user |
| **/api/users/** | `PATCH` | **username** - user name <br> **email** - user email <br> **plainPassword** - password | edit user |
| **/api/users/{userId}** | `DELETE` | ```null``` | delete user |

