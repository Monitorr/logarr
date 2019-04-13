    <!-- footer.php -->
    <div id="footer">

        <!-- Checks for Logarr application update on page load & "Check for update" click: -->
        <script src="assets/js/update.js"></script>

        <div id="logarrid">
            <a href="https://github.com/monitorr/logarr" title="Logarr GitHub Repo" target="_blank"
            class="footer">Logarr </a> |
            
            <a href="https://github.com/Monitorr/logarr/releases" title="Logarr Releases" target="_blank" class="footer">
                v: <?php echo file_get_contents(__DIR__ . "/../../js/version/version.txt"); ?>
            </a> |
            <a href="settings.php" title="Logarr Settings" target="_blank" class="footer">Settings</a>
            <br>
        </div>

    </div>

    <!-- Close persistant tooltips: -->
    <script>
        $(window).blur(function(){
            $('a').blur();
        });
    </script>

</body>

</html>
