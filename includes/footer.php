<footer class=" bg-dark text-white">
    <?php if ($_SESSION['status'] == 'admin') { ?>
    <ul class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small">
        <li>
            <a  class="nav-link foot-nav" href="<?=$prefUrl?>addAuthor.php">Add Author</a>
        </li>
        <li>
            <a  class="nav-link foot-nav" href="<?=$prefUrl?>manageLoans.php">Manage loans</a>
        </li>
        <li>
            <a  class="nav-link foot-nav" href="<?=$prefUrl?>promote.php">Promote member</a>
        </li>
        <li>
            <a  class="nav-link foot-nav" href="<?=$prefUrl?>bookAttrib.php">Attrib location</a>
        </li>
    </ul>
    <?php } else if ($_SESSION['status'] == 'membre') {
        include 'includes/tagcloud.inc.php';
    } ?>
    <p class="text-center text-muted copyright">&copy; EPFC 2022, Made with <strong>❤</strong> by <a id="mail" class="mail text-decoration-none" href="mailto:cdujardin4000@gmail.com">cdujardin4000</a></p>
</footer>

<!-- Bootstrap Bundle with Popper -->
<script src=<?=$prefUrl."js/bootstrap.bundle.min.js"?>></script>
<script src=<?=$prefUrl."js/jquery.js"?>></script>
<script src=<?=$prefUrl."js/main.js"?>></script>

</body>
</html>
