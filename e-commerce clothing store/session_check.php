<?php
session_start();

if (isset($_SESSION['user_id'])) {
    echo "✅ Logged in as: " . $_SESSION['email'];
} else {
    echo "❌ Not logged in";
}
