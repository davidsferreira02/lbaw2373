## EAP



 


### A7: Web Resources Specification

The web application architecture that has to be developed is described, along with a list of resources and their attributes, such as graphical interface references and response formats in JSON. 


### 1. Overview

Identify and overview the modules that will be part of the application.

|Module| Description|
|---|---|
| M01: Static Pages| Website pages connected with static information such as about us page and Contact us|  
| M02 : Authentication|Web resources with user authentication,includes register,login,logout and passorwd recovery |   
| M03 :Authenticated User|Web resources related to managing and customizing the user's personal area and deleting the account.| 
| M04 : Project Area|The project area holds all member-related information and is responsible for managing the project hierarchy.|
|M05:Administrator Area |Web resources associated with the administrator capabilities of the organization include user management, which enables the administrator to add or remove users from the workspace.|


### 2. Permissions

Define the permissions used by each module, necessary to access its data and features.

| Identifier| Name|Description | 
|---|---|---|
|Pub| Public | Users without privileges| 
| OWN|Owner  | The owner of the content like project,task or comment| 
|USR|User  | Authenticated users| 
| PM|Project Member | Member of a project|
| PL|Project Leader | Leader of a project| 
|ADM|Admin |System administrator |





### 3. OpenAPI Specification

OpenAPI specification in YAML format to describe the vertical prototype's web resources.


[Link to a7_openapi.yaml file](https://git.fe.up.pt/lbaw/lbaw2324/lbaw2373/-/blob/main/EAP/a7_openapi.yaml?ref_type=heads)



```yaml 
openapi: 3.0.0

info:
  version: '1.0'
  title: 'LBAW Project TaskSquad'
  description: 'Web Resources Specification (A7) for TaskSquad'

servers:
- url: http://lbaw2371.lbaw.fe.up.pt
  description: Production server

tags:
  - name: 'M01: Static Pages'
  - name: 'M02: Authentication'
  - name: 'M03: Authenticated User Area'
  - name: 'M04: Project Area'
  - name: 'M05:  Admin Page'

paths:
#-------------------------------- M01 -----------------------------------------


 /:
    get:
      operationId: R101
      summary: 'R101: Home Page'
      description: 'See the home page when you access the app. Access: PUB'
      tags:
      - 'M01: Static Pages'
      responses:
       '200':
        description: 'Ok. Show homepage' 
 
 /about-us/:
    get:
      operationId: R102
      summary: 'R102: About Us'
      description: 'Provide description about website and its creators. Access: PUB'
      tags:
      - 'M01: Static Pages'
      responses:
       '200':
        description: 'Ok. Show About Us page' 

 /404:
    get:
      operationId: R
      summary: "R805: Not Found Page"
      description: "Show Nexus Not Found page. Access: PUB"
      tags:
        - "M08: User Administration and Static pages"

      responses:
        "302":
          description: "Ok. Show features page UI"
        "404":
          description: "Page not available"

       

#-------------------------------- M02 -----------------------------------------
 /login:
    get:
      operationId: R201
      summary: 'R201: Login Form'
      description: 'Provide login form. Access: PUB'
      tags:
        - 'M02: Authentication'
      responses:
        '200':
          description: 'Ok. Show log-in UI'
    post:
      operationId: R202
      summary: 'R202: Login Action'
      description: 'Processes the login form submission. Access: PUB'
      tags:
        - 'M02: Authentication'
 
      requestBody:
        required: true
        content:
          application/x-www-form-urlencoded:
            schema:
              type: object
              properties:
                email:          # <!--- form field name
                  type: string
                password:    # <!--- form field name
                  type: string
              required:
                - email
                - password
 
      responses:
        '302':
          description: 'Redirect after processing the login credentials.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Success:
                  description: 'Successful authentication. Redirect to projects page.'
                  value: '/projects'
                302Error:
                  description: 'Failed authentication. Redirect to login form.'
                  value: '/login'

 /register:
    get:
      operationId: R203
      summary: 'R203: Register Form'
      description: 'Provide new user registration form. Access: PUB'
      tags:
        - 'M02: Authentication'
      responses:
        '200':
          description: 'Ok. Show sign-up UI'

    post:
      operationId: R204
      summary: 'R204: Register Action'
      description: 'Processes the new user registration form submission. Access: PUB'
      tags:
        - 'M02: Authentication'

      requestBody:
        required: true
        content:
          application/x-www-form-urlencoded:
            schema:
              type: object
              properties:
                name:
                  type: string
                email:
                  type: string
              required:
                - name
                - email
                - password

      responses:
        '302':
          description: 'Redirect after processing the new user information.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Success:
                  description: 'Successful authentication. Redirect to the projects page.'
                  value: '/projects'
                302Failure:
                  description: 'Failed authentication. Redirect to login form.'
                  value: '/login'
 
 /password/recovery:
    get:
      operationId: R205
      summary: 'R205: Password Recovery Form'
      description: 'Provide password recovery form. Access: PUB'
      tags:
        - 'M02: Authentication'
      responses:
        '200':  
          description: 'Ok. Show password recovery form'
    post:
      operationId: R206
      summary: 'R206: Recover Password Action'
      description: "Recover a user's password"
      tags:
        - 'M02: Authentication'

      requestBody:
        required: true
        content:
          application/x-www-form-urlencoded:
            schema:
              type: object
              properties:
                inputEmail:
                  type: string
              required:
                - inputEmail

      responses:
        '303':
          description: 'Redirect after processing password recovery request'
          headers:
            Location:
              schema:
                type: string
              examples:
                303Success:
                  description: 'Ok. Redirect to login.'
                  value: '/login/'
                303Error:
                  description: 'Failed password recovery. Redirect to password recovery.'
                  value: '/login/'

 # ---------------------------- M03 -----------------------------------------------
  
 /logout:

    post:
      operationId: R301
      summary: 'R301: Logout Action'
      description: 'Logout the current authenticated used. Access: USR, ADM'
      tags:
        - 'M03: Authenticated User Area'
      responses:
        '302':
          description: 'Redirect after processing logout.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Success:
                  description: 'Successful logout. Redirect to login form.'
                  value: '/login'
 /userpage:
  get:
    operationId: R302
    summary: 'R302: View user profile'
    description: 'Show the individual user profile. Access: OWN'
    tags:
      - 'M03: Authenticated User Area'

    responses:
      '200':
        description: 'Ok. Show view profile UI'
        content:
          application/json:
            schema:
              type: object
              properties:
                name:
                  type: string
                email:
                  type: string
                description:
                  type: string

  
 /edituserpage:
  get:
    operationId: R303
    summary: 'R303: Edit user profile form'
    description: 'Show the individual user profile edit form. Access: OWN'
    tags:
      - 'M03: Authenticated User Area'
    parameters:
      - in: path
        name: id
        schema:
          type: integer
        required: true
    responses:
      '200':
        description: 'Ok. Show edit profile UI'
        content:
          application/json:
            schema:
              type: object
              properties:
                name:
                  type: string
                email:
                  type: string
                description:
                  type: string
  post:
    operationId: R304
    summary: 'R304: Edit user profile action'
    description: 'Edit user profile. Access: OWN'
    tags:
      - 'M03: Authenticated User Area'
    requestBody:
      required: true
      content:
        application/x-www-form-urlencoded:
          schema:
            type: object
            properties:
              inputName:
                type: string
              inputEmail:
                type: string
              inputDescription:
                type: string
              inputProfilePicture:
                type: string
    responses:
      '302':
        description: 'Redirect after processing the new user information.'
        headers:
          Location:
            schema:
              type: string
            examples:
              302Success:
                description: 'Information successfully processed. Redirect to user profile.'
                value: '/userpage'
              302Failure:
                description: 'Failed to process information. Redirect to edit user information form.'
                value: '/edituserpage'

 /deleteuser:
    get:
      operationId: R305
      summary: "R305: Delete User"
      description: 'Delete User account. Access: OWN'
      tags:
        - 'M03: Authenticated User Area'

      responses:
        '200':
          description: 'Ok. Account Deleted'
 /changePassword:
  get:
    operationId: R306
    summary: "R306: Change user's password form"
    description: 'Show the password change form. Access: OWN'
    tags:
      - 'M03: Authenticated User Area'
    responses:
      '200':
        description: 'Ok. Show edit profile UI'
  post:
    operationId: R307
    summary: "R307: Change user's password"
    description: "Change user's password"
    tags:
      - 'M03: Authenticated User Area'
    requestBody:
      required: true
      content:
        application/x-www-form-urlencoded:
          schema:
            type: object
            properties:
              inputName:
                type: string
              inputEmail:
                type: string
              inputDescription:
                type: string
    responses:
      '302':
        description: 'Redirect after processing the new user information.'
        headers:
          Location:
            schema:
              type: string
            examples:
              302Success:
                description: 'Information successfully processed. Redirect to user profile.'
                value: '/userpage'
              302Failure:
                description: 'Failed to process information. Redirect to edit user information form.'
                value: '/edituserpage'

 /profile/{username}:
    get:
      operationId: R308
      summary: 'R308: Profile page'
      description: 'Show the profile page . Access: USR'
      tags:
        - 'M03: Authenticated User Area'

      parameters:
        - in: path
          name: username
          schema:
            type: string
          required: true

      responses:
        '200':
          description: 'Ok. Show profile page'
        '404':
            description: 'Profile not found'          

 /search/{query}:
    get:
      operationId: R309
      summary: "R309: Search"
      description: "Show Search hits page. Access: PUB"
      tags:
        - 'M03: Authenticated User Area'

      parameters:
        - in: query
          name: query
          description: 'Queried String'
          schema: 
            type: string
          required: true

      responses:
        "302":
          description: "Ok. Show search page"             

 /create-project:
  get:
    operationId: R401
    summary: 'R401: Create New Project Form'
    description: 'Create New Project. Access: USR'
    tags:
      - 'MO4: Project Area'
    responses:
      '200':
        description: 'Ok. Show edit profile UI'
        content:
          application/json:
            schema:
              type: array
              items:
                type: object
                properties:
                  company:
                    type: string

 /api/project/create:
  post:
    operationId: R402
    summary: 'R402: Create New Project Action'
    description: 'Create New Project. Access: USR'
    tags:
      - 'M04: Project Area'
    requestBody:
      required: true
      content:
        application/x-www-form-urlencoded:
          schema:
            type: object
            properties:
              inputName:
                type: string
              inputCompanyID:
                type: integer
              inputDescription:
                type: string
              inputAssignedMembers:
                type: string
    responses:
      '302':
        description: 'Redirect after processing the project information.'
        headers:
          Location:
            schema:
              type: string
            examples:
              302Success:
                description: 'Success. Redirect to project area.'
                value: '/project/{id}'
              302Error:
                description: 'Failed. Redirect to project creation form.'
                value: '/create-project/'

 /project/{id}:
  get:
    operationId: R403
    summary: "R403: See project page"
    description: "See the project page and all its information. Access: PM"
    tags:
      - 'M04: Project Area'
    parameters:
      - in: path
        name: id
        schema:
          type: integer
        required: true
    responses:
      '200':
        description: 'Ok. Show project page UI'
        content:
          application/json:
            schema:
              type: object
              properties:
                name:
                  type: string
                members:
                  type: array
                  items:
                    $ref: '#/components/schemas/User'
  delete:
    operationId: R404
    summary: "R404: Delete Project"
    description: "Delete a Project. Access: PL"
    tags:
      - 'M04: Project Area'
    parameters:
      - in: path
        name: id
        schema:
          type: integer
        required: true
    responses:
      '200':
        description: 'Project deleted'

 /api/load-users:
  get:
    operationId: R405
    summary: 'R405: Load Users'
    description: 'Load Users. Access: PM'
    tags:
      - 'M04: Project Area'
    responses:
      '200':
        description: "Return users for pagination"
        content: 
          application/json:
            schema:
              type: object
              properties:
                posts:
                  type: array
                  items:
                    $ref: '#/components/schemas/User'
      '400':
        description: "Error in parameters"
            
 /project/{project_id}/search:
  get:
    operationId: R406
    summary: 'R406: Search Tasks API'
    description: 'Search for tasks and return the results as JSON. Access: PM.'
    tags: 
      - 'M04: Project Area'
    parameters:
      - in: query
        name: query
        description: String to use for full-text search
        schema:
          type: string
        required: true
    responses:
      '200':
        description: Success
        content:
          application/json:
            schema:
              type: array
              items: 
                $ref: '#/components/schemas/Task'
                   
 /api/task/{project_id}:
  put:
    operationId: R407
    summary: "R407: Create new task"
    description: "Create a new task. Access: PM"
    tags:
      - 'M04: Project Area'
    requestBody:
      required: true
      content:
        application/x-www-form-urlencoded:
          schema:
            type: object
            properties:
              task-name:
                type: string
              task-description:
                type: string
              task-members:
                type: array
              dead-line:
                type: string
                items:
                  $ref: '#/components/schemas/User'
              due-date:
                type: string
    responses:
      '201':
        description: 'Task created'

 /api/task/{id}:
  get:
    operationId: R408
    summary: "R408: Get task info"
    description: "Get the task's information. Access: PM"
    tags:
      - 'M04: Project Area'
    parameters:
      - in: path
        name: id
        schema:
          type: integer
        required: true
    responses:
      '200':
        description: 'Ok. Show task page UI'
        content:
          application/json:
            schema:
              type: object
              properties:
                name:
                  type: string
                members:
                  type: array
                dead-line:
                  type: array  
                  items:
                    $ref: '#/components/schemas/Task'
  delete:
    operationId: R409
    summary: "R409: Delete task"
    description: "Delete a task. Access: OWN"
    tags:
      - 'M04: Project Area'
    parameters:
      - in: path
        name: id
        schema:
          type: integer
        required: true
    responses:
      '200':
        description: 'Task deleted'
  post:
      operationId: R410
      summary: "R410: Update task info"
      description: "Update the task's information. Access: PM"
      tags:
        - 'M04: Project Area'
      requestBody:
        required: true
        content:
          application/x-www-form-urlencoded:
            schema:
              type: object
              properties:
                task-name:
                  type: string
                task-description:
                  type: string
                task-members:
                  type: array
                  items:
                    $ref: '#/components/schemas/User'
                dead-line:
                  type: string
      responses:
        '200':
          description: 'Task updated'
 /admin:
     get:
      operationId: R501
      summary: "R501: Admin Page"
      description: "Show administrator page. Access: ADM"
      tags:
        - "M05:Admin Pages"

      responses:
        "302":
          description: "Ok. Show admin UI"
        "404": 
          description: "Page not available"



      
# -------------------- Components --------------------
  
components:
  schemas:
    Task:
      type: object
      properties:
        description:
          type: string
        start_date:
          type: string
        delivery_Date:
          type: string
        status:
          type: string
        project_member:
          type: array
        
          items:
            $ref: '#/components/schemas/User'
        photo:
          type: string

    User:
        type: object
        properties:
          name:
            type: string
          email: 
            type: string
          profile_description:
            type: string
          profile_image:
            type: string


```

### A8: Vertical prototype

Brief presentation of the artifact goals.


1. Implemented Features

1.1. Implemented User Stories

Identify the user stories that were implemented in the prototype.


User Story  reference |	Name|Priority| Description|
| ----------- | -------------------------------------------------------|-----------|---------|
|US01| Name of the user story	 | Priority of the user story|Description of the user story









...

#### 1.2. Implemented Web Resources

Identify the web resources that were implemented in the prototype.


Module M01: Module Name




Web Resource Reference
URL




R01: Web resource name
URL to access the web resource



...

Module M02: Module Name

...

2. Prototype

URL of the prototype plus user credentials necessary to test all features.
Link to the prototype source code in the group's git repository.



Revision history
Changes made to the first submission:

Item 1
..


GROUP2373, 20/10/2023

    David dos Santos Ferreira , up202006302@fc.up.pt (Editor)
    Ana Sofia Oliveira Teixeira , up201806629@fe.up.pt
    Ana Sofia Silva Pinto , up202004606@fc.up.pt
    Gabriela Dias Salazar Neto Silva , up202004443@fe.up.pt
