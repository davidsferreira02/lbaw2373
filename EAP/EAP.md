## EAP


### A7: Web Resources Specification

The web application architecture that has to be developed is described, along with a list of resources and their attributes, such as graphical interface references and response formats in JSON. The following operations over data are available on this page: generate, view, amend, and remove

### 1. Overview

Identify and overview the modules that will be part of the application.

|Module| Description|
|---|---|
| M01: Static Pages| Website pages connected with static information such as about us page and home page|
| M02 : Authentication|Web resources with user authentication,includes register,login,logout and password recovery |
| M03 :Authenticated User Area|Web resources related to managing and customizing the user's personal area and deleting the account.| 
| M04 : Project Area|The project area holds all project-related information and is responsible for searching the tasks.|
|M05 : Task Area|The task area holds all task-related information and comments.|
|M05 : Admin Pages |Web resources associated with the administrator capabilities of the organization include user and project management.|


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


[Link to a7_openapi.yaml file](https://git.fe.up.pt/lbaw/lbaw2324/lbaw2373/-/blob/main/EAP/a7_openapi.yaml?ref_type=heads&plain=0)



```yaml openapi: 3.0.0
openapi: 3.0.0

info:
  version: '1.0'
  title: 'LBAW Project TaskSquad'
  description: 'Web Resources Specification (A7) for TaskSquad'

servers:
- url: http://lbaw2373.lbaw.fe.up.pt
  description: Production server

tags:
  - name: 'M01: Static Pages'
  - name: 'M02: Authentication'
  - name: 'M03: Authenticated User Area'
  - name: 'M04: Project Area'
  - name: 'M05: Task Area'
  - name: 'M06: Admin Pages'

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
      operationId: R103
      summary: "R103: Not Found Page"
      description: "Show TaskSquad Not Found page. Access: PUB"
      tags:
        - 'M01: Static Pages'
      responses:
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
                email:
                  type: string
                password:
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
                password:
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
                  description: 'Failed authentication. Redirect to register form.'
                  value: '/register'
 
  /password-recovery:
    get:
      operationId: R205
      summary: 'R205: Password Recovery Form'
      description: 'Provide password recovery form. Access: PUB'
      tags:
        - 'M02: Authentication'
      responses:
        '200':  
          description: 'Ok. Show password recovery form'
    put:
      operationId: R206
      summary: "R206: Change User's Password Form"
      description: 'Show the Password Change Form. Access: OWN'
      tags:
        - 'M02: Authentication'
      requestBody:
        required: true
        content:
          application/x-www-form-urlencoded:
            schema:
              type: object
              properties:
                password:
                  type: string
      responses:
        '302':
          description: 'Redirect after processing password recovery request'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Success:
                  description: 'Ok. Redirect to login.'
                  value: '/login'
                302Error:
                  description: 'Failed password recovery. Redirect to password recovery.'
                  value: '/password-recovery'
  /logout:
    post:
      operationId: R207
      summary: 'R207: Logout Action'
      description: 'Logout the current authenticated used. Access: USR, ADM'
      tags:
        - 'M02: Authentication'
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

 # ---------------------------- M03 -----------------------------------------------
  
  /profile/{username}:
    parameters:
    - in: path
      name: username
      schema:
        type: string
      required: true
    get:
      operationId: R301
      summary: 'R301: View User Profile Page'
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
    delete:
      operationId: R302
      summary: "R302: Delete User Profile"
      description: "Delete User account. Access: OWN"
      tags:
        - 'M03: Authenticated User Area'
      responses:
        '200':
          description: 'Ok. Account deleted'
          
  
  /profile/{username}/edit:
    parameters:
    - in: path
      name: username
      schema:
        type: string
      required: true
    get:
      operationId: R303
      summary: 'R303: Edit User Profile Form'
      description: 'Show the individual user profile edit form. Access: OWN'
      tags:
        - 'M03: Authenticated User Area'
      parameters:
        - in: path
          name: username
          schema:
            type: integer
          required: true
      responses:
        '200':
          description: 'Ok. Show edit profile UI'
    put:
      operationId: R304
      summary: 'R304: Edit User Profile Action'
      description: 'Edit User Profile. Access: OWN'
      tags:
        - 'M03: Authenticated User Area'
      requestBody:
        required: true
        content:
          application/x-www-form-urlencoded:
            schema:
              type: object
              properties:
                username:
                  type: string
                email: 
                  type: string
                name:
                  type: string
                birthdate:
                  type: integer
                profile_pic:
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
                  value: '/profile/{username}'
                302Failure:
                  description: 'Failed to process information. Redirect to edit user information form.'
                  value: '/profile/{username}/edit'


  /api/search/{query}:
    parameters:
    - in: path
      name: query
      schema:
        type: string
      required: true
    get:
      operationId: R305
      summary: "R305: Search"
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
        "200":
          description: "Ok. Show search page"             

#-------------------------------- M04 -----------------------------------------

  /create-project:
    get:
      operationId: R401
      summary: 'R401: Create New Project Form'
      description: 'Create New Project. Access: USR'
      tags:
        - 'M04: Project Area'
      responses:
        '200':
          description: 'Ok. Show Create Project UI'
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
                title:
                  type: string
                description:
                  type: string
                theme:
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
                  value: '/create-project'

  /project/{id}:
    parameters:
    - in: path
      name: id
      schema:
        type: integer
      required: true
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
  
  /project/{id}/edit:
    parameters:
    - in: path
      name: id
      schema:
        type: integer
      required: true
    get:
      operationId: R405
      summary: 'R405: Edit Project Form'
      description: 'Show the project edit form. Access: OWN'
      tags:
        - 'M04: Project Area'
      responses:
        '200':
          description: 'Ok. Show edit project UI'
    put:
      operationId: R406
      summary: 'R406: Edit Project Action'
      description: 'Edit Project. Access: OWN'
      tags:
        - 'M04: Project Area'
      requestBody:
        required: true
        content:
          application/x-www-form-urlencoded:
            schema:
              type: object
              properties:
                title:
                  type: string
                description:
                  type: string
                theme:
                  type: string
      responses:
        '302':
          description: 'Redirect after processing the new project information.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Success:
                  description: 'Information successfully processed. Redirect to project page.'
                  value: '/project/{id}'
                302Failure:
                  description: 'Failed to process information. Redirect to edit project form.'
                  value: '/project/{id}/edit'
        
              
  /api/{project_id}/search/{query}:
    parameters:
    - in: path
      name: project_id
      schema:
        type: integer
      required: true
    - in: path
      name: query
      schema:
        type: string
      required: true
    get:
      operationId: R407
      summary: 'R407: Search Tasks API'
      description: 'Search for tasks and return the results as JSON. Access: PM.'
      tags: 
        - 'M04: Project Area'
      parameters:
        - in: query
          name: query
          description: 'String to use for full-text search'
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
  


#-------------------------------- M05 -----------------------------------------


  /{project_id}/task:
    parameters:
    - in: path
      name: project_id
      schema:
        type: integer
      required: true
    post:
      operationId: R501
      summary: "R501: Create new task"
      description: "Create a new task. Access: PM"
      tags:
        - 'M05: Task Area'
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
                    $ref: '#/components/schemas/Task'
                dead-line:
                  type: string
                  items:
                    $ref: '#/components/schemas/User'
                due-date:
                  type: string
      responses:
        '200':
          description: 'Task Created'

  /{project_id}/task/{task_id}:
    parameters:
    - in: path
      name: project_id
      schema:
        type: integer
      required: true
    - in: path
      name: task_id
      schema:
        type: integer
      required: true
    get:
      operationId: R502
      summary: "R502: Get task info"
      description: "Get the task's information. Access: PM"
      tags:
        - 'M05: Task Area'
      responses:
        '200':
          description: 'Ok. Show task page UI'
    delete:
      operationId: R503
      summary: "R503: Delete task"
      description: "Delete a task. Access: OWN"
      tags:
        - 'M05: Task Area'
      responses:
        '200':
          description: 'Task deleted'
          
  /{project_id}/task/{task_id}/edit:
    parameters:
    - in: path
      name: project_id
      schema:
        type: integer
      required: true
    - in: path
      name: task_id
      schema:
        type: integer
      required: true
    get:
      operationId: R504
      summary: "R504: Edit Task Form"
      description: "Get the edit task form. Access: OWN"
      tags:
        - 'M05: Task Area'
      responses:
        '200':
          description: 'Ok. Show edit task page UI'
    put:
        operationId: R505
        summary: "R505: Update task info"
        description: "Update the task's information. Access: OWN"
        tags:
          - 'M05: Task Area'
        requestBody:
          required: true
          content:
            application/x-www-form-urlencoded:
              schema:
                type: object
                properties:
                  priority:
                    type: string
                  content:
                    type: string
                  is_completed:
                    type: boolean
                  date_creation:
                    type: integer
                  deadline:
                    type: integer
                  title:
                    type: string
                  id_project:
                    type: array
                    items:
                      $ref: '#/components/schemas/Project'
        responses:
          '302':
            description: 'Redirect after processing the new task information.'
            headers:
              Location:
                schema:
                  type: string
                examples:
                  302Success:
                    description: 'Information successfully processed. Redirect to task page.'
                    value: '/{project_id}/task/{task_id}'
                  302Failure:
                    description: 'Failed to process information. Redirect to edit task form.'
                    value: '/{project_id}/task/{task_id}/edit'
          

  /api/{task_id}/comments:
    parameters:
    - in: path
      name: task_id
      schema:
        type: integer
      required: true
    post:
      operationId: R506
      summary: "R506: Create a Comment for a Task"
      description: "Create a comment for a task. Access: PM"
      tags:
        - 'M05: Task Area'
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              properties:
                text:
                  type: string
      responses:
        '200':
          description: 'Comment created'
      
  /api/{comment_id}:
    parameters:
    - in: path
      name: comment_id
      schema:
        type: integer
      required: true
    put:
      operationId: R507
      summary: "R507: Edit a Comment for a Task"
      description: "Edit a comment for a task. Access: PM"
      tags:
        - 'M05: Task Area'
      parameters:
        - in: path
          name: comment_id
          schema:
            type: integer
          required: true
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              properties:
                text:
                  type: string
      responses:
        '200':
          description: 'Comment updated'
    delete:
      operationId: R508
      summary: "R508: Delete a Comment for a Task"
      description: "Delete a comment for a task. Access: PM"
      tags:
        - 'M05: Task Area'
      parameters:
        - in: path
          name: comment_id
          schema:
            type: integer
          required: true
      responses:
        '200':
          description: 'Comment deleted'



#-------------------------------- M06 -----------------------------------------


  /admin:
    get:
      operationId: R601
      summary: "R601: Admin Pages"
      description: "Show administrator page. Access: ADM"
      tags:
        - "M06: Admin Pages"
      responses:
        "302":
          description: "Ok. Show admin UI"
        "404": 
          description: "Page not available"
  
  /admin/browse/users:
    get:
      operationId: R602
      summary: 'R602: Browse Users API'
      description: 'Show Browse Users page. Access: ADM.'
      tags: 
        - 'M06: Admin Pages'
      responses:
        '200':
          description: 'Success'
          content:
            application/json:
              schema:
                type: array
                items: 
                  $ref: '#/components/schemas/User'
  
  /admin/browse/projects:
    get:
      operationId: R603
      summary: 'R603: Browse Projects API'
      description: 'Show Browse Projects page. Access: ADM.'
      tags: 
        - 'M06: Admin Pages'
      responses:
        '200':
          description: 'Success'
          content:
            application/json:
              schema:
                type: array
                items: 
                  $ref: '#/components/schemas/Project'
  
      
# -------------------- Components --------------------
  
components:
  schemas:
    User:
      type: object
      properties:
        id:
          type: integer
        username:
          type: string
        password:
          type: string
        email: 
          type: string
        
    Generic_User:
      type: object
      properties:
        id:
          type: integer
        name:
          type: string
        birthdate:
          type: integer
        profile_pic:
          type: string
        isBanned:
          type: boolean
        
    Project:
      type: object
      properties:
        id:
          type: integer
        title:
          type: string
        description:
          type: string
        theme:
          type: string
        archived:
          type: boolean
        
    Task:
      type: object
      properties:
        id:
          type: integer
        priority:
          type: string
        content:
          type: string
        is_completed:
          type: boolean
        date_creation:
          type: integer
        deadline:
          type: integer
        title:
          type: string
        id_project:
          type: array
          items:
            $ref: '#/components/schemas/Project'
    
    Comment:
      type: object
      properties:
        id:
          type: integer
        content:
          type: string
        date:
          type: integer
    
    Likes:
      type: object
      properties:
        id:
          type: integer
        comment_id:
          type: array
          items:
            $ref: '#/components/schemas/Comment'
        generic_user_id:
          type: array
          items:
            $ref: '#/components/schemas/Generic_User'
        
        
    Is_Admin:
      type: object
      properties:
        id:
          type: integer
        user_id:
          type: array
          items:
            $ref: '#/components/schemas/User'
    
    Favorite:
      type: object
      properties:
        id:
          type: integer
        project_id:
          type: array
          items:
            $ref: '#/components/schemas/Project'
        generic_user_id:
          type: array
          items:
            $ref: '#/components/schemas/Generic_User'
        
    Notification:
      type: object
      properties:
        id:
          type: integer
        description:
          type: string
    
    Project_Notification:
      type: object
      properties:
        id:
          type: integer
        project_id:
          type: array
          items:
            $ref: '#/components/schemas/Project'
        notification_id:
          type: array
          items:
            $ref: '#/components/schemas/Notification'
        user_id:
          type: array
          items:
            $ref: '#/components/schemas/User'
        project_type:
          type: string
    
    Task_Notification:
      type: object
      properties:
        id:
          type: integer
        task_id:
            type: array
            items:
              $ref: '#/components/schemas/Task'
        notification_id:
          type: array
          items:
            $ref: '#/components/schemas/Notification'
        user_id:
          type: array
          items:
            $ref: '#/components/schemas/User'
        task_type:
          type: string
    
    Comment_Notification:
      type: object
      properties:
        id:
          type: integer
        comment_id:
            type: array
            items:
              $ref: '#/components/schemas/Comment'
        notification_id:
          type: array
          items:
            $ref: '#/components/schemas/Notification'
        user_id:
          type: array
          items:
            $ref: '#/components/schemas/User'
        comment:
          type: string
    
    Is_Member:
      type: object
      properties:
        id_user:
          type: array
          items:
            $ref: '#/components/schemas/User'
        id_project:
          type: array
          items:
            $ref: '#/components/schemas/Project'
        
    Is_Leader:
      type: object
      properties:
        id_user:
          type: array
          items:
            $ref: '#/components/schemas/User'
        id_project:
          type: array
          items:
            $ref: '#/components/schemas/Project'
      
    Task_Owner:
      type: object
      properties:
        id_user:
          type: array
          items:
            $ref: '#/components/schemas/User'
        id_task:
          type: array
          items:
            $ref: '#/components/schemas/Task'
      
    Assigned:
      type: object
      properties:
        id_user:
          type: array
          items:
            $ref: '#/components/schemas/User'
        id_task:
          type: array
          items:
            $ref: '#/components/schemas/Task'
      
    Comment_Owner:
      type: object
      properties:
        id_user:
          type: array
          items:
            $ref: '#/components/schemas/User'
        id_comment:
          type: array
          items:
            $ref: '#/components/schemas/Comment'

```

### A8: Vertical prototype

The Vertical Prototype is where the most significant user stories are implemented. It is also used to familiarize users with the project's technology and validate the provided architecture. During the implementation phase, the user interface, business logic, and data access layers of the solution's architecture are all being worked on using the LBAW Framework. The prototype covers various functionalities, including information visualization pages' execution, information entry, editing, and removal, permission control for accessing the implemented pages, and error and success messages display.

### 1. Implemented Features

#### 1.1. Implemented User Stories

The preceding section illustrates the user stories that have been integrated into the prototype.


User Story  reference |	Name|Priority| Description|
| ----------- | -------------------------------------------------------|-----------|---------|
|US01| Sign-in | High |As a Visitor, I want to authenticate into the system, so that I can access privileged information|
|US02| Sign-up | High |As a Visitor, I want to register myself into the system, so that I can authenticate myself into the system|
|US12| Home | High | As a User, I want to access the home page, so that I can see a brief presentation of the website |
|US21| Create Project | High | Authenticated User can create a Project |
|US22| View My Projects | High | As an authenticated user can see all my projects, whether I'm a member or a leader |
|US24| View Profile | High | As an authenticated user, I can see my profile whenever I want |
|US25| Edit Profile | High | As an authenticated user, I can edit my profile whenever I want |
|US41| Create Task | High | As a project member can create a task |
|US42| Manage Task | High | As a project member you can assign a property to a task |
|US43| View Task Details | High | A member can see all the details of tasks with priority or content |
|US44| Complete an Assigned Task | High | As a project member you can complete the task assigned to you|
|US45| Search Tasks | High | As member from the project can search for any tasks of the project |
|US46| Assign Users to Tasks | High | As a project member  can assign a task to a person |
|US48| Assigned to Task | High | If you're assigned a task to do, you'll receive a notification about it |
|US51| Add user to Project | High | As a project leader you can add several users to your project |


#### 1.2. Implemented Web Resources

The subsequent section outlines the web resources integrated into the prototype.

##### Module M01: Static Pages
|Web Resource Reference|URL|
|-----|-----|
|R101: Home Page| GET /home |


##### Module M02: Authentication
|Web Resource Reference|URL|
|-----|-----|
|R201: Login Form| GET /login|
|R202: Login Action| POST /login|
|R203: Register Form| GET /register|
|R204: Register Action| POST /register|
|R207: Logout Action| POST /logout|

##### Module M03: Authenticated User Area
|Web Resource Reference|URL|
|-----|-----|
|R301: View User Profile Page| GET /profile/{id}|
|R302: Edit User Profile Form| GET /profile/{id}/edit|
|R303: Edit User Profile Action| PUT /profile/{id}/edit|
|R304: Search users or project| GET /search/users|

##### Module M04: Project Area
|Web Resource Reference|URL|
|-----|-----|
|R401: Create New Project Form| GET /create-project|
|R402: Create New Project Action| POST /projects|
|R403: See Project Page| GET /project/{title} |
|R404: See My Projects | GET /myProjects |
|R405: AddMember Form| GET /project/{title}/addMember |
|R406: AddMember Action| POST /project/{title}/addMember/store |
|R407: AddLeader Form| GET /project/{title}/addLeader |
|R408: AddLeader Action| POST /project/{title}/addLeader/store |
|R409: Pending Invites from the Leader| GET /pending-invites |
|R410: Accept Invite| POST /accept-invite/{id_user}/{id_project} |
|R411: Decline Invite| POST /decline-invite/{id_user}/{id_project}|
|R412: Show Members| GET /project/{title}/members |
|R413: Show Leaders| GET /project/{title}/leaders |


##### Module M05: Task Area
|Web Resource Reference|URL|
|-----|-----|
|R501: Create New Task Form| GET /project/{title}/createTask|
|R502: Create New Task Action| POST /project/{title}/storeTask|
|R503: See and search tasks| GET /project/{title}/task|
|R504: Mark as Completed| PATCH /project/{title}/task/{taskId}/complete|

##### Module M06: Admin Area
|Web Resource Reference|URL|
|-----|-----|
|R601: Admin Pages| GET /admin|


#### 2. Prototype
The prototype is available at [here](https://lbaw2373.lbaw.fe.up.pt)

Credentials:
* admin user:
    * email: bob@example.com
    * password:1234
* regular user:
    * email:david@lbaw.com
    * password:1234
    * email:alice@example.com
    * password:1234

The code is available [here](https://git.fe.up.pt/lbaw/lbaw2324/lbaw2373).


Revision history
    
    Changes made to the first submission:
    Item 1
    ..


GROUP2373, 20/11/2023

    David dos Santos Ferreira , up202006302@fc.up.pt (Editor)
    Ana Sofia Oliveira Teixeira , up201806629@fe.up.pt
    Ana Sofia Silva Pinto , up202004606@fc.up.pt
    Gabriela Dias Salazar Neto Silva , up202004443@fe.up.pt