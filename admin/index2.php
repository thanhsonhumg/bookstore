<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sidebars</title>
    <link rel="stylesheet" href="http://localhost/bookstore/css/styleindex2.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,700,1,0"
    />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
    <script src="https://unpkg.com/akar-icons-fonts"></script>
  </head>
  <body>
    <aside class="sidebar">
      <header>
        <img src="logo.svg" />
      </header>
      <ul>
        <li>
          <input type="radio" id="dashboard" name="sidebar" />
          <label for="dashboard">
            <i class="ai-dashboard"></i>
            <p>Dashboard</p>
          </label>
        </li>
        <li>
          <input type="radio" id="settings" name="sidebar" />
          <label for="settings">
            <i class="ai-gear"></i>
            <p>Settings</p>
            <i class="ai-chevron-down-small"></i>
          </label>
          <div class="sub-menu">
            <ul>
              <li>
                <button type="button">Display</button>
              </li>
              <li>
                <button type="button">Appearance</button>
              </li>
              <li>
                <button type="button">Preferences</button>
              </li>
            </ul>
          </div>
        </li>
        <li>
          <input type="radio" id="create" name="sidebar" />
          <label for="create">
            <i class="ai-folder-add"></i>
            <p>Create</p>
            <i class="ai-chevron-down-small"></i>
          </label>
          <div class="sub-menu">
            <ul>
              <li>
                <button type="button">Article</button>
              </li>
              <li>
                <button type="button">Document</button>
              </li>
              <li>
                <button type="button">Video</button>
              </li>
              <li>
                <button type="button">Presentation</button>
              </li>
            </ul>
          </div>
        </li>
        <li>
          <input type="radio" id="profile" name="sidebar" />
          <label for="profile">
            <i class="ai-person"></i>
            <p>Profile</p>
            <i class="ai-chevron-down-small"></i>
          </label>
          <div class="sub-menu">
            <ul>
              <li>
                <button type="button">Avatar</button>
              </li>
              <li>
                <button type="button">Theme</button>
              </li>
            </ul>
          </div>
        </li>
        <li>
          <input type="radio" id="notifications" name="sidebar" />
          <label for="notifications">
            <i class="ai-bell"></i>
            <p>Notifications</p>
          </label>
        </li>
        <li>
          <input type="radio" id="products" name="sidebar" />
          <label for="products">
            <i class="ai-cart"></i>
            <p>Products</p>
          </label>
        </li>
        <li>
          <input type="radio" id="account" name="sidebar" />
          <label for="account">
            <i class="ai-lock-on"></i>
            <p>Account</p>
          </label>
        </li>
      </ul>
    </aside>
  </body>
</html>