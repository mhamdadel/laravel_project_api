<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="/Dashboard">Dashboard</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link unsigned" href="/login">login</a>
      </li>
      <li class="nav-item">
        <a class="nav-link unsigned" href="/register">register</a>
      </li>
      <li class="nav-item">
        <a id="nameInNav" class="nav-link signed"></a>
      </li>
      <li class="nav-item">
        <a class="nav-link signed" href="/logout">logout</a>
      </li>
    </ul>
  </div>
</nav>


<script>
  if (localStorage.getItem('token')) {
    document.querySelectorAll('.unsigned').forEach(e => e.style.display = "none");
    document.querySelectorAll('.signed').forEach(e => e.style.display = "inline-block");
    document.querySelector('#nameInNav').innerText = (localStorage.getItem('name'));
  } else {
    document.querySelectorAll('.unsigned').forEach(e => e.style.display = "inline-block");
    document.querySelectorAll('.signed').forEach(e => e.style.display = "none");
    document.querySelector('#nameInNav').innerText = "";
  }
</script>