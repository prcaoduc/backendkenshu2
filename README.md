# backendkenshu2
## CONFIG
http port : 9999
My project routing from index.php, so the URL gonna be like this index.php?(ROUTES) or index?controller=(CONTROLLER_NAME)&action=(ACTION_NAME)&id=(ITEM_ID)
A complete routes for example : localhost:9999/index.php?controller=articles&action=show&id=1
## ROUTES:
### Pages Routes : 
#### Home (GET) : ?controller=pages&action=home
#### Error (GET) : ?controller=pages&action=error
### Articles Route :
#### Index (GET) : ?controller=articles&action=index
#### Show (GET) : ?controller=articles&action=show&id=(ID)
#### Add (GET) : ?controller=articles&action=add
#### Create (POST) : ?controller=articles&action=create
#### Edit (GET) : ?controller=articles&action=update
#### Update (POST) : ?controller=articles&action=delete
#### Delete (POST) : ?controller=articles&action=edit&id=(ID)
### Users Routes : 
#### Index (GET) : ?controller=users&action=index
#### Show (GET) : ?controller=users&action=show&id=(ID)
#### User's Articles (GET) : ?controller=users&action=articles
### Authentications Routes : 
#### Login (GET) : ?controller=authentications&action=login
#### Signin (POST) : ?controller=authentications&action=signin
#### Register (GET) : ?controller=authentications&action=register
#### Check (GET) : ?controller=authentications&action=check
#### Signup (POST) : ?controller=authentications&action=signup
#### Thanks (GET) : ?controller=authentications&action=thanks
#### Logout (POST) : ?controller=authentications&action=logout
