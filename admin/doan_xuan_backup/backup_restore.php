<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Backup and Restore</title>
</head>
<body>
    <form action="backup_restore.php" method="post">
        <button type="submit" name="backup">Backup Database</button>
    </form>
    <br>
    <form action="backup_restore.php" method="post" enctype="multipart/form-data">
        <input type="file" name="restore_file" accept=".sql">
        <button type="submit" name="restore">Restore Database</button>
    </form>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['backup'])) {
        backup_tables('localhost', 'root', '', 'doan_xuan_backup');
    } elseif (isset($_POST['restore'])) {
        if (isset($_FILES['restore_file']) && $_FILES['restore_file']['error'] == 0) {
            $filename = $_FILES['restore_file']['tmp_name'];
            restore_tables('localhost', 'root', '', 'doan_xuan_backup', $filename);
        } else {
            echo "Error: No file uploaded or there was an error uploading the file.";
        }
    }
}

/* Sao lưu cả database hoặc một bảng cụ thể nào đó */
function backup_tables($host, $user, $pass, $name, $tables = '*')
{
    $link = new mysqli($host, $user, $pass, $name);
    $link->set_charset('utf8');

    if ($link->connect_error) {
        die("Connection failed: " . $link->connect_error);
    }

    // Lấy tất cả các bảng
    if ($tables == '*') {
        $tables = array();
        $result = $link->query('SHOW TABLES');
        while ($row = $result->fetch_row()) {
            $tables[] = $row[0];
        }
    } else {
        $tables = is_array($tables) ? $tables : explode(',', $tables);
    }

    $return = '';

    // Vòng lặp
    foreach ($tables as $table) {
        $result = $link->query('SELECT * FROM ' . $table);
        $num_fields = $result->field_count;

        $return .= 'DROP TABLE IF EXISTS ' . $table . ';';
        $row2 = $link->query('SHOW CREATE TABLE ' . $table)->fetch_row();
        $return .= "\n\n" . $row2[1] . ";\n\n";

        while ($row = $result->fetch_row()) {
            $return .= 'INSERT INTO ' . $table . ' VALUES(';
            for ($j = 0; $j < $num_fields; $j++) {
                $row[$j] = addslashes($row[$j]);
                $row[$j] = preg_replace("/\n/", "\\n", $row[$j]);
                if (isset($row[$j])) {
                    $return .= '"' . $row[$j] . '"';
                } else {
                    $return .= '""';
                }
                if ($j < ($num_fields - 1)) {
                    $return .= ',';
                }
            }
            $return .= ");\n";
        }
        $return .= "\n\n\n";
    }

    // Save file
    $handle = fopen('diepdb-backup-' . time() . '-' . (md5(implode(',', $tables))) . '.sql', 'w+');
    fwrite($handle, $return);
    fclose($handle);
    echo "Backup completed successfully!";
}

/* Khôi phục database từ file backup */
function restore_tables($host, $user, $pass, $name, $filename)
{
    $link = new mysqli($host, $user, $pass, $name);
    $link->set_charset('utf8');

    if ($link->connect_error) {
        die("Connection failed: " . $link->connect_error);
    }

    // Vô hiệu hóa các ràng buộc khóa ngoại
    $link->query('SET foreign_key_checks = 0');

    $templine = '';
    $lines = file($filename);
    $delimiter = ';';
    foreach ($lines as $line) {
        // Bỏ qua các dòng chứa lệnh DELIMITER
        if (strpos($line, 'DELIMITER') !== false) {
            $delimiter = trim(explode(' ', $line)[1]);
            continue;
        }

        // Bỏ qua các dòng comment và dòng trống
        if (substr($line, 0, 2) == '--' || $line == '') {
            continue;
        }

        $templine .= $line;
        
        // Nếu dòng kết thúc bằng delimiter hiện tại
        if (substr(trim($line), -strlen($delimiter)) == $delimiter) {
            // Loại bỏ delimiter để thực thi câu lệnh
            $templine = substr($templine, 0, -strlen($delimiter));

            $link->query($templine) or print('Error performing query \'' . $templine . '\': ' . $link->error . '<br /><br />');
            $templine = '';
        }
    }

    // Bật lại các ràng buộc khóa ngoại
    $link->query('SET foreign_key_checks = 1');

    echo "Database restored successfully!";
}
?>

