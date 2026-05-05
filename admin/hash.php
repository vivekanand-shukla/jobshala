<?php
echo password_hash('admin123', PASSWORD_BCRYPT);
echo "<br>";
echo password_hash('master123', PASSWORD_BCRYPT);
?>