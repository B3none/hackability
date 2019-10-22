<?php
require('../inc/functions.inc.php');

header("Content-Security-Policy: sandbox; default-src 'none';style-src 'self'");

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
?>
<!doctype HTML>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="robots" content="noindex" />
    <title>Inspector</title>
    <link href="<?= htmlentities(getUrl(), ENT_QUOTES)?>styles.css" rel="stylesheet" />
</head>
<body>
<?php
require("../inc/inspectorLogger.class.php");

$logger = new InspectorLogger();
$logger->init();

?>

<?php if ($logger): ?>
    <?php if (!$id): ?>
        <?php
        $sql = 'SELECT * FROM inspection_log ORDER BY date_of_request DESC';
        $result = $logger->query($sql);
        ?>
        <table class="displayTable">
            <tr>
                <th>Date of request</th>
                <th>Object name</th>
                <th>User agent</th>
                <th>IP</th>
            </tr>

            <?php while($row = $result->fetchArray(SQLITE3_ASSOC)): ?>
                <tr>
                    <td><?= htmlentities($row['date_of_request'], ENT_QUOTES) ?></td>
                    <td><a href="display.php?id=<?= ((int) $row['ID']) ?>"><?= htmlentities($row['object_name'], ENT_QUOTES)?></a></td>
                    <td><?= htmlentities($row['user_agent'], ENT_QUOTES) ?></td>
                    <td><?= htmlentities($row['ip'], ENT_QUOTES) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <?php
        $sql = "SELECT html FROM inspection_log WHERE ID = :id LIMIT 100";
        $prepareStatement = $logger->prepare($sql);
        $prepareStatement->bindParam(':id', $id);
        $result = $prepareStatement->execute();
        $found = false;

        while($row = $result->fetchArray(SQLITE3_ASSOC)):
        ?>
            <?php $found = true; ?>
            <?= $row['html']; ?>
        <?php endwhile; ?>

        <?php if (!$found): ?>
            <b>Unable to find record.</b>
        <?php endif; ?>
    <?php endif; ?>

    <?php $logger->close(); ?>
<?php else: ?>
    <h1>Unable to open database.</h1>
<?php endif; ?>
</body>
</html>
