<aside class="sidebar signed">
  <ul class="nav flex-column">
    <li class="nav-item">
      <a class="nav-link" href="{{ url()->route('dashboard.users') }}">Users</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ url()->route('dashboard.products') }}">Products</a>
    </li>
  </ul>
</aside>


<script>
  if (localStorage.getItem('token')) {
    document.querySelectorAll('.unsigned').forEach(e => e.style.display = "none");
    document.querySelectorAll('.signed').forEach(e => e.style.display = "inline-block");
  } else {
    document.querySelectorAll('.unsigned').forEach(e => e.style.display = "inline-block");
    document.querySelectorAll('.signed').forEach(e => e.style.display = "none");
  }
</script>