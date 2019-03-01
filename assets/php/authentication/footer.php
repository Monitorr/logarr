    <div id="footer">

        <!-- Checks for Logarr application update on page load & "Check for update" click: -->
        <script src="assets/js/update.js" async></script>

        <div id="logarrid">
            <a href="https://github.com/monitorr/logarr" title="Logarr GitHub repo" target="_blank"
            class="footer">Logarr </a> |
            
            <a href="https://github.com/Monitorr/logarr/releases" title="Logarr releases" target="_blank" class="footer">
                Version: <?php echo file_get_contents(__DIR__ . "/../../js/version/version.txt"); ?>
            </a> |
            <a href="settings.php" title="Logarr Settings" target="_blank" class="footer">Settings</a>
            <br>
        </div>

    </div>

</body>

</html>
