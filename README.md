# riddle-api-client

## Getting Started

Tested with:

- PHP 8.2.6
- NODE 18.15.0

This is a simple client demo for the [riddle-api](https://www.riddle.com/creator/v3/docs) written in Symfony and Vue.

This demo will retrieve all riddles that are accessible with the access token and display them in a dropdown button. When a riddle is selected the riddle will be displayed in an modal overlay and the user can interact with the riddle. After completion the riddle will be marked as completed and the user can select another riddle.

Completed Riddles will be fileted client side and only uncompleted riiddles will be displayed. In your application you can store this information in a database and only retrieve new riddles for the user.

### Symfony backend

#### Prerequisites

Create a .env.local file in the symfony folder and add the following line:

```bash
RIDDLE_ACCESS_TOKEN=add_your_access_token_here
```

All riddles that are accassible with the access token will be retrieved. Only published or modified riddles will be returned.

#### Installation

```bash
cd symfony
composer install
symfony server:start
```

### Vue frontend

The app will start as local.riddle.com. You have to add this to your hosts file. The app will not work with localhost.

#### Installation

```bash
cd vue
npm install
npm run dev
```
