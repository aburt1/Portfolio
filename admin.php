<?php session_start();  ?>

<?php
if (!isset($_SESSION['use'])) {
    header("Location:index.php");
}

if (isset($_POST['delete'])) {
    $filename = $_POST['delete'];
    unlink($filename);
    unset($_POST['delete']);
    header("Location:admin.php");
}

if (isset($_POST['hide'])) {
    $filename = $_POST['hide'];
    $image = explode('.', $filename);
    $newname =  $image[0] . '-hidden.' . $image[1];
    rename($filename, $newname);
    unset($_POST['hide']);
    header("Location:admin.php");
}

if (isset($_POST['unhide'])) {
    $filename = $_POST['unhide'];
    $search = '-hidden';
    $trimmed = str_replace($search, '', $filename);
    rename($filename, $trimmed);
    unset($_POST['unhide']);
    header("Location:admin.php");
}

if (isset($_POST['rotate'])) {
    $filename = $_POST['rotate'];
    $source = imagecreatefromjpeg($filename);
    $rotate = imagerotate($source, 90, 0);
    imagejpeg($rotate, $filename);
    imagedestroy($source);
    imagedestroy($rotate);
    unset($_POST['rotate']);
    header("Location:admin.php");
}

if (isset($_POST['rename'])) {
    $fileOriginal = $_POST['fileOriginal'];
    $fileNew = 'images/' . $_POST['fileNew'];
    if (strpos($fileOriginal, '-hidden') !== false) {
        rename($fileOriginal, $fileNew . "-hidden." . pathinfo($fileOriginal, PATHINFO_EXTENSION));
    } else {
        rename($fileOriginal, $fileNew . "." . pathinfo($fileOriginal, PATHINFO_EXTENSION));
    }
    header("Location:admin.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Portal</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" integrity="sha384-1CmrxMRARb6aLqgBO7yyAxTOQE2AKb9GfXnEo760AUcUmFx3ibVJJAzGytlQcNXd" crossorigin="anonymous">
    </script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="js/dropzone.min.js"></script>
    <link href="css/dropzone.min.css" rel="stylesheet">
    <link href="css/admin.css" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon-16x16.png">
    <link rel="manifest" href="assets/site.webmanifest">
</head>

<body>
    <section class="container py-4">
        <div class="row">
            <div class="col-md-12">
                <a class="navbar-brand" href="#">
                    <img src="./assets/android-chrome-192x192.png" width="30" height="30" alt="">
                    Photo Management
                </a>
                <ul id="tabsJustified" class="nav nav-tabs nav-fill">
                    <li class="nav-item"><a href="" data-target="#manage" data-toggle="tab" class="nav-link small text-uppercase active">Manage</a></li>
                    <li class="nav-item"><a href="" data-target="#home" data-toggle="tab" class="nav-link small text-uppercase">Upload</a></li>
                    <li class="nav-item"><a href="./logout.php" onclick="location.href='./logout.php';" class="nav-link small text-uppercase">Logout</a></li>
                </ul>
                <br>
                <div id="tabsJustifiedContent" class="tab-content">
                    <div id="manage" class="tab-pane fade active show">
                        <table class="table-bordered table-hover" style="width:30%;">
                            <?php
                            $folder_path = 'images/';
                            $num_files = glob($folder_path . "*.{JPG,jpg,gif,png}", GLOB_BRACE);
                            $folder = opendir($folder_path);
                            if ($num_files > 0) {
                                while (false !== ($file = readdir($folder))) {
                                    $file_name = $file;
                                    $file_path = $folder_path . $file;
                                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                    if ($extension == 'jpg' || $extension == 'png' || $extension == 'gif') {
                            ?>
                                        <tr>
                                            <td>
                                                <img loading="lazy" class="center-cropped" src="<?php echo $file_path; ?>?=<?php echo rand() . "\n"; ?>" height="100">
                                            </td>
                                            <td>
                                                <p><?php echo $file_name ?></p>
                                            </td>
                                            <td>
                                                <?php
                                                if (strpos($file_path, '-hidden') !== false) { ?>
                                                    <form method="post" action="admin.php">
                                                        <button class="btn btn-outline-dark" type="submit" name="unhide" value="<?php echo $file_path; ?>">Unhide</button>
                                                    </form>
                                                <?php } else { ?>
                                                    <form method="post" action="admin.php">
                                                        <button class="btn btn-outline-dark" type="submit" name="hide" value="<?php echo $file_path; ?>">Hide</button>
                                                    </form>
                                                <?php  } ?>
                                            </td>
                                            <td>
                                                <form method="post" action="admin.php">
                                                    <button class="btn btn-outline-warning" type="submit" name="rotate" value="<?php echo $file_path; ?>">Rotate 90&deg;</button>
                                                </form>
                                            </td>
                                            <td>
                                                <?php echo "<button class=\"btn btn-outline-success\" onclick=\"openRename('" . $file_path . "')\">Rename</button>" ?>
                                                <div class="form-popup" id="rename">
                                                    <form method="post" action="admin.php" class="form-container">
                                                        <p>What would you like to rename this file too?</p>
                                                        <img loading="lazy" class="center-cropped" id="renameImage" src="" height="100">
                                                        <hr>
                                                        <input type="hidden" id="renameOriginal" name="renameOriginal" value="">
                                                        <input class="form-control" type="text" name="fileNew">
                                                        <button class="btn btn-danger" type="submit" name="rename" value="rename">Rename</button>
                                                        <button type="button" class="btn cancel" onclick="closeRename()">Cancel</button>
                                                        <!-- <p id="fileRename"></p> -->
                                                    </form>
                                                </div>
                                            </td>
                                            <td>
                                                <?php echo "<button class=\"btn btn-danger\" onclick=\"openDelete('" . $file_path . "')\">Delete</button>" ?>
                                                <div class="form-popup" id="delete">
                                                    <form method="post" action="admin.php" class="form-container">
                                                        <p>Are you sure you would like to delete this file?</p>
                                                        <img loading="lazy" class="center-cropped" id="deleteImage" src="" height="100">
                                                        <hr>
                                                        <button class="btn btn-danger" type="submit" id="deleteOriginal" name="delete" value="">Yes</button>
                                                        <button type="button" class="btn cancel" onclick="closeDelete()">Cancel</button>
                                                        <!-- <p id="fileDelete"></p> -->
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                            <?php
                                    }
                                }
                            } else {
                                echo "The folder was empty!";
                            }
                            closedir($folder);
                            ?>
                        </table>
                    </div>
                    <div id="home" class="tab-pane fade">
                        <div id="upload">
                            <div class="col-lg-12 col-lg-offset-6 text-center">
                                <br>
                                <form action="upload.php" class="dropzone" id="lmao"></form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <br>
    <script>
        function openDelete(filePath) {
            document.getElementById("rename").style.display = "none";
            document.getElementById("delete").style.display = "block";
            //document.getElementById("fileDelete").innerHTML = filePath;
            document.getElementById('deleteOriginal').value = filePath;
            document.getElementById('deleteImage').src = filePath + "?=" + Math.floor(Math.random() * 100001);
        }

        function closeDelete() {
            document.getElementById("delete").style.display = "none";
        }

        function openRename(filePath) {
            document.getElementById("delete").style.display = "none";
            document.getElementById("rename").style.display = "block";
            //document.getElementById("fileRename").innerHTML = filePath;
            document.getElementById('renameOriginal').value = filePath;
            document.getElementById('renameImage').src = filePath + "?=" + Math.floor(Math.random() * 100001);
        }

        function closeRename() {
            document.getElementById("rename").style.display = "none";
        }
    </script>
</body>

</html>