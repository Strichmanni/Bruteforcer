<?php
function brute_force($url, $username_target, $password_target, $max_length) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ^!°"§$%&/()=?`*+#;,:._-»<>|€~\}][{@';
    $attempts = 0;
    $start_time = microtime(true);

    for ($username_length = 1; $username_length <= $max_length; $username_length++) {
        for ($password_length = 1; $password_length <= $max_length; $password_length++) {
            $username_combinations = generate_combinations($chars, $username_length);
            $password_combinations = generate_combinations($chars, $password_length);

            foreach ($username_combinations as $username) {
                foreach ($password_combinations as $password) {
                    $attempts++;
                    if ($username === $username_target && $password === $password_target) {
                        $end_time = microtime(true);
                        $elapsed_time = $end_time - $start_time;
                        return array($username, $password, $attempts, $elapsed_time);
                    }
                   
                }
            }
        }
    }
    return array(null, null, $attempts, null);
}

function generate_combinations($chars, $length) {
    $combinations = array();
    $chars_length = strlen($chars);
    $total = pow($chars_length, $length);

    for ($i = 0; $i < $total; $i++) {
        $combination = '';
        $temp = $i;
        for ($j = 0; $j < $length; $j++) {
            $combination = $chars[$temp % $chars_length] . $combination;
            $temp = intdiv($temp, $chars_length);
        }
        $combinations[] = $combination;
    }

    return $combinations;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $url = $_POST['target_url'];
    $username_target = $_POST['username_target'];
    $password_target = $_POST['password_target'];
    $max_length = intval($_POST['max_length']);

    $result = brute_force($url, $username_target, $password_target, $max_length);
    
    if ($result[0] && $result[1]) {
        echo "Username '{$result[0]}' and Password '{$result[1]}' cracked in {$result[2]} attempts and " . number_format($result[3], 2) . " seconds.";
    } else {
        echo "Failed to crack the username and password in {$result[2]} attempts.";
    }
}
?>
