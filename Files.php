<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        File Dashboard
    </title>

    <script src="https://cdn.jsdelivr.net/npm/appwrite@11.0.0"></script>

    <link rel="shortcut icon" href="/images/company.png" type="image/png">
    <!-- GOOGLE FONT -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <!-- BOXICONS -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <!-- APP CSS -->
    <link rel="stylesheet" href="./css/grid.css">
    <link rel="stylesheet" href="./css/app.css">

    <!-- PHP SCRIPTS -->

    <?php
    require __DIR__ . '/vendor/autoload.php';

    use Appwrite\Client;
    use Appwrite\Services\Users;
    use Appwrite\Services\Storage;

    $client = new Client();

    $client
        ->setEndpoint('http://51.161.212.158:9191/v1') // Your API Endpoint
        ->setProject('64511dda13070874dfb6') // Your project ID
        ->setKey('95fb218c695522b2f45167e2fc2a2770238998350663d0a839af6481fb310a904a3db0a570e16651622852d3f2acf694043fc36ea528153a37dd382d919deae3e887d8b5dc9dd8fe92c1a7d67265885296987692fd732210fb6646d137d3c2dbf6d037fa7b87b8a008a715e10c781b14945c2900ecbb9602ad48521bf6c13d08') // Your secret API key
    ;

    $users = new Users($client);

    $result = $users->list();
    echo '<script>';
    echo 'let TotalUsers = ' . json_encode($result) . '';
    echo '</script>';

    function GetBuckets()
    {

        $client = new Client();

        $client
            ->setEndpoint('http://51.161.212.158:9191/v1') // Your API Endpoint
            ->setProject('64511dda13070874dfb6') // Your project ID
            ->setKey('95fb218c695522b2f45167e2fc2a2770238998350663d0a839af6481fb310a904a3db0a570e16651622852d3f2acf694043fc36ea528153a37dd382d919deae3e887d8b5dc9dd8fe92c1a7d67265885296987692fd732210fb6646d137d3c2dbf6d037fa7b87b8a008a715e10c781b14945c2900ecbb9602ad48521bf6c13d08') // Your secret API key
        ;

        $storage = new Storage($client);
        $result2 = $storage->listBuckets();

        $result3 = json_encode($result2);
        print_r($result3);


    }
    ?>

    <!-- END PHP SCRIPTS -->


    <!-- SCRIPTS -->
    <script>
        const { Client, Account, ID, Storage, Query, Databases, Locale } = Appwrite;

        let FileExtArray = [0, 0];

        CheckAuth();

        function RequestDivider() {
            console.log("");
        }

        function CheckAuth() {

            const client = new Client()
                .setEndpoint('http://51.161.212.158:9191/v1') // Your API Endpoint
                .setProject('64511dda13070874dfb6');  // Your project ID

            const account = new Account(client);
            const promise = account.getSession('current');

            promise.then(function (response) {
                console.log("[!] => START <= [!]");

                console.log("  => Get CheckAuth: Success"); // Success

                GetUserName();
                //GetTotalFiles();
                //GetTotalSize();
                GatherBuckets();
                //GetFiles('FileBin');
                GetActions();
                //LoopRequests();

            }, function (error) {
                console.log(error); // Failure

                window.location.href = "Login.php";
            });
        }

        function GetUserName() {
            const client = new Client()
                .setEndpoint('http://51.161.212.158:9191/v1') // Your API Endpoint
                .setProject('64511dda13070874dfb6');               // Your project ID

            const account = new Account(client);

            const promise = account.getSession('current');

            promise.then(function (response) {
                document.getElementById('UserName').innerHTML = response.providerUid;
                console.log("  => Get UserName: Success"); // Success


                document.getElementById('TotalUsersCard').innerHTML = TotalUsers.total;
                console.log("  => Get TotalUsers: Success"); // Success


            }, function (error) {
                console.log("  => Get UserName: FAILED -> | " + error); // Failure
                console.log("  => Get TotalUsers: FAILED -> | " + error); // Failure
            });
        }



        function GatherBuckets() {

            ListBuckets();
        }

        function ListBuckets() {

            var BucketArrayPHP = JSON.stringify(<?php GetBuckets(); ?>);
            var BucketArray = JSON.parse(BucketArrayPHP);
            console.log(BucketArray);
            let i = 0;
            let total = 0;
            let totalsize = 0;

            document.getElementById('BucketTable').innerHTML = ' ';
            while (i <= BucketArray.total - 1) {
                let BucketName = BucketArray.buckets[i].name;
                let BucketID = BucketArray.buckets[i].$id;

                const client = new Client()
                    .setEndpoint('http://51.161.212.158:9191/v1') // Your API Endpoint
                    .setProject('64511dda13070874dfb6');               // Your project ID

                const storage = new Storage(client);

                storage.listFiles(BucketID).then(function (response) {
                    let x = 0;
                    let size = 0;
                    let sizeunit = "|MB";
                    total = total + response.files.length;
                    document.getElementById('TotalFilesCard').innerHTML = total;

                    while (x <= response.files.length - 1) {
                        console.log(response.files[x].sizeOriginal + ' | ' + x);
                        size = size + (response.files[x].sizeOriginal) / 1000000;
                        totalsize = totalsize + (response.files[x].sizeOriginal);
                        document.getElementById('TotalSizeCard').innerHTML = (totalsize / 1000000).toFixed(2) + sizeunit;
                        sizeunit = "|MB";
                        x = x + 1;
                    }
                    document.getElementById('BucketTable').insertAdjacentHTML('beforeend',
                        (`<tr>
                                        <td> <i class='bx bx-folder'></i> <btn onclick="GetFiles('` + BucketID + `')">` + BucketName + `</btn> </td>
                                        <td style="text-align: center;">` + response.total + `</td>
                                        <td style="text-align: right;">` + size.toFixed(2) + sizeunit + `</td>
                                    </tr>`));

                    console.log("  => Get TotalFiles: Success"); // Success

                }, function (error) {
                    console.log("  => Get TotalFiles: FAILED -> | " + error);
                });



                i = i + 1;
            }

        }



        function GetFiles(BucketID) {
            const client = new Client()
                .setEndpoint('http://51.161.212.158:9191/v1') // Your API Endpoint
                .setProject('64511dda13070874dfb6');               // Your project IDsss

            const storage = new Storage(client);

            storage.listFiles(BucketID).then(function (response) {

                let FileArray = response.files;


                let i = 0;
                document.getElementById('FileTable').innerHTML = '';
                while (i <= FileArray.length - 1) {
                    let FileName = FileArray[i].name;
                    let FileSize = 0;
                    let FileExt = "NULL";
                    //let FileDate =FileArray[i].$created;
                    let FileID = FileArray[i].$id;
                    let SizeUnit = "NULL";

                    if (Math.trunc(FileArray[i].sizeOriginal / 1000000) === 0) {
                        FileSize = (FileArray[i].sizeOriginal / 1000000) * 1000;
                        SizeUnit = "|KB";
                    }
                    else if (Math.trunc(FileArray[i].sizeOriginal / 1000000) === 1) {
                        FileSize = (FileArray[i].sizeOriginal / 1000000);
                        SizeUnit = "|MB";
                    }


                    if (FileArray[i].name.includes(".exe")) {
                        FileExtArray[0] = FileExtArray[0] + 1;
                        FileExt = "Executable";
                    } else if (FileArray[i].name.includes(".png")) {
                        FileExtArray[1] = FileExtArray[1] + 1;
                        FileExt = "Image File";
                    }

                    document.getElementById('FileTable').insertAdjacentHTML('beforeend',
                        (`<tr>
                                        <td style="text-align: left;">` + FileName + `</td>
                                        <td style="text-align: center;">` + FileSize.toFixed(2) + SizeUnit + `</td>
                                        <td style="text-align: center;">` + FileExt + `</td>
                                        <td style="text-align: center;">
                                            <span class="action-tag Download">
                                            <btn onclick="Download('` + BucketID + `', '` + FileID + `', '` + FileName + `')"><i class='bx bx-download' ></i></btn>
                                            </span>
                                        </td>
                                        <td style="text-align: center;">
                                            <span class="action-tag Delete">
                                            <btn onclick="Delete('` + BucketID + `', '` + FileID + `', '` + FileName + `')"><i class='bx bx-trash' ></i></btn>
                                            </span>
                                        </td>
                                        <td style="text-align: right;">` + FileID + `</td>
                                    </tr>`));

                    document.getElementById('FileHeader').innerHTML = (
                        `Files
                            <input type="file" id="uploader" oninput="Upload('` + BucketID + `')" hidden />
                            <span class="action-tag Upload">
                                <label for="uploader"><i class='bx bx-upload' ></i></label>
                            </span>`
                    );

                    i = i + 1;
                }

                console.log("  => Get LargeFiles: Success"); // Success


                //MakeExtChart();


            }, function (error) {
                console.log("  => Get LargeFiles: FAILED -> | " + error);
            });
        }



        let ActionCount = 'NULL';

        function GetActions() {
            const client = new Client()
                .setEndpoint('http://51.161.212.158:9191/v1') // Your API Endpoint
                .setProject('64511dda13070874dfb6');               // Your project ID


            const databases = new Databases(client);

            const promise = databases.listDocuments(
                'Dashboard',
                'ActionLog',
                [
                    Query.select(['File', 'User', 'Date', 'Action', 'Response', 'Source'])
                ]
            ).then(function (response) {
                let i = 0;
                let ActionArray = response.documents;
                ActionCount = ActionArray.length;


                document.getElementById('TotalActionsCard').innerHTML = ActionCount;
                console.log("  => Get Actions: Success"); // Success



                setTimeout(() => {
                    console.log("[!] => END <= [!]");
                    RequestDivider();
                }, 1000);


            }, function (error) {
                console.log("  => Get Actions: FAILED -> | " + error);
            });

        }

        function LogOut() {

            const client = new Client()
                .setEndpoint('http://51.161.212.158:9191/v1') // Your API Endpoint
                .setProject('64511dda13070874dfb6');  // Your project ID

            const account = new Account(client);

            const promise = account.deleteSession('current');

            promise.then(function (response) {
                console.log("  => Get LogOut: Success"); // Success
                CheckAuth();
            }, function (error) {
                console.log("  => Get LogOut: FAILED -> | " + error);
            });
        }

        let ActionLogArray = new Array;
        let DateArray = new Array;

        async function GetRequests(i) {

            const client = new Client()
                .setEndpoint('http://51.161.212.158:9191/v1') // Your API Endpoint
                .setProject('64511dda13070874dfb6'); // Your project ID

            const databases = await new Databases(client);

            var today = new Date();
            var options = { year: 'numeric', month: 'numeric', day: 'numeric' };
            var DaysAgo = new Date(today.getTime() - (i * 24 * 60 * 60 * 1000));
            const year = DaysAgo.getFullYear();
            const dayOfWeek = new Intl.DateTimeFormat('en-US', { weekday: 'short' }).format(DaysAgo);
            const month = String(DaysAgo.getMonth() + 1).padStart(2, '0');
            const day = String(DaysAgo.getDate()).padStart(2, '0');
            var isoDate = `${year}-${month}-${day}`;
            var Day = today.getDay();



            let promise = databases.listDocuments(
                'Dashboard',
                'ActionLog',
                [
                    Query.equal('Date', [isoDate])
                ]);

            setTimeout(() => {
                promise.then(function (response) {
                    let object = response.documents;
                    console.log('     ->', response.total, isoDate, i, object);
                    ActionLogArray[(7 - i)] = response.total;
                    DateArray[(7 - i)] = dayOfWeek;

                }, function (error) {
                    console.log("  => Get ActionLogs: FAILED -> | " + error);
                });
                setTimeout(() => {

                }, 500);
            }, 100);

        }

        async function LoopRequests() {
            let i = 7;
            while (i > -1) {
                GetRequests(i);
                i = i - 1;
            }

            setTimeout(() => {
                MakeReqChart();
                console.log('  => Get ActionLogs: Success')
            }, 1000);
        }

        function Download(BucketID, FileID, FileName) {
            //console.log(BucketID + ' | ' + FileID);

            const client = new Client();

            const storage = new Storage(client);

            client
                .setEndpoint('http://51.161.212.158:9191/v1') // Your API Endpoint
                .setProject('64511dda13070874dfb6') // Your project ID
                ;

            const result = storage.getFileDownload(BucketID, FileID);

            console.log(result); // File URL
            window.open(result, '_blank');
            LogAction(FileName, 'Download', 'Success');
        }

        function Delete(BucketID, FileID, FileName) {


            const client = new Client();

            const storage = new Storage(client);

            client
                .setEndpoint('http://51.161.212.158:9191/v1') // Your API Endpoint
                .setProject('64511dda13070874dfb6') // Your project ID
                ;

            const result = storage.deleteFile(BucketID, FileID);

            console.log(result); // File URL

            result.then(function (response) {
                console.log(response); // Success
                GetFiles(BucketID);
                LogAction(FileName, 'Delete', 'Success');
            }, function (error) {
                console.log(error); // Failure
                LogAction(FileName, 'Delete', 'Failed');
            });
        }

        function Upload(BucketID) {
            let FileN = document.getElementById('uploader').files[0].name;
            const client = new Client();

            const storage = new Storage(client);

            client
                .setEndpoint('http://51.161.212.158:9191/v1') // Your API Endpoint
                .setProject('64511dda13070874dfb6') // Your project ID
                ;

            const promise = storage.createFile(
                BucketID,
                ID.unique(),

                document.getElementById('uploader').files[0]
            );

            promise.then(function (response) {
                console.log(response); // Success
                GetFiles(BucketID);
                LogAction(FileN, 'Upload', 'Success');
            }, function (error) {
                console.log(error); // Failure
                LogAction(FileN, 'Upload', 'Failed');
            });
        }

        function LogAction(FileName, Action, Response) {

            var today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            var isoDate = `${year}-${month}-${day}`;
            let iDate = isoDate;

            const client = new Client();

            const locale = new Locale(client);

            const databases = new Databases(client);

            client
                .setEndpoint('http://51.161.212.158:9191/v1') // Your API Endpoint
                .setProject('64511dda13070874dfb6') // Your project ID
                ;

            const Lpromise = locale.get();

            Lpromise.then(function (response) {
                console.log(response); // Success
                let Source = response.ip;

                const Apromise = databases.createDocument('Dashboard', 'ActionLog', ID.unique(),
                    {
                        "File": FileName,
                        "User": document.getElementById('UserName').innerHTML,
                        "Date": iDate,
                        "Action": Action,
                        "Response": Response,
                        "Source": Source,
                    }
                );

                Apromise.then(function (response) {
                    console.log(response); // Success
                    console.log(Source);
                }, function (error) {
                    console.log(error); // Failure
                    console.log(Source);
                });

            }, function (error) {
                console.log(error); // Failure
            });
        }




    </script>


</head>


<body>


    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="./images/company-large.png" alt="Company Logo" />

            <div class="sidebar-close" id="sidebar-close">
                <i class='bx bx-left-arrow-alt'></i>
            </div>
        </div>
        <div class="sidebar-user">
            <div class="sidebar-user-info">
                <img src="./images/user-image-2.png" alt="User picture" class="profile-image">
                <div id="UserName" class="sidebar-user-name" style="text-transform: lowercase;">
                    Logged in user
                </div>
            </div>
            <button class="btn btn-outline" onclick="LogOut()">
                <i class='bx bx-log-out bx-flip-horizontal'></i>
            </button>
        </div>
        <!-- SIDEBAR MENU -->
        <ul class="sidebar-menu">
            <li>
                <a href="./Dashboard.php">
                    <i class='bx bx-home'></i>
                    <span>dashboard</span>
                </a>
            </li>


            <li>
                <a href="#" class="active">
                    <i class='bx bx-hdd'></i>
                    <span>Files</span>
                </a>
            </li>


            <li class="sidebar-submenu">
                <a href="#" class="sidebar-menu-dropdown">
                    <i class='bx bx-user-circle'></i>
                    <span>account</span>
                    <div class="dropdown-icon"></div>
                </a>
                <ul class="sidebar-menu sidebar-menu-dropdown-content">
                    <li>
                        <a href="#">
                            edit profile
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            account settings
                        </a>
                    </li>
                </ul>


            <li class="sidebar-submenu">
                <a href="#" class="sidebar-menu-dropdown">
                    <i class='bx bx-cog'></i>
                    <span>settings</span>
                    <div class="dropdown-icon"></div>
                </a>
                <ul class="sidebar-menu sidebar-menu-dropdown-content">
                    <li>
                        <a href="#" class="darkmode-toggle" id="darkmode-toggle">
                            darkmode
                            <span class="darkmode-switch"></span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->

    <!-- MAIN CONTENT -->
    <div class="main">

        <div class="main-header">
            <div class="mobile-toggle" id="mobile-toggle">
                <i class='bx bx-menu-alt-right'></i>
            </div>
            <div class="main-title">
                file storage
            </div>
        </div>
        <div class="main-content">

            <div class="row">


                <div class="col-3 col-md-6 col-sm-12">
                    <div class="counter-card box-hover">
                        <!-- CARD 1 -->
                        <div class="counter">
                            <div class="counter-title">
                                total files
                            </div>
                            <div class="counter-info">
                                <div id="TotalFilesCard" class="counter-count">
                                    Files
                                </div>
                                <i class='bx bx-data'></i>
                            </div>
                        </div>
                        <!-- END CARD 1 -->
                    </div>
                </div>
                <div class="col-3 col-md-6 col-sm-12">
                    <div class="counter-card box-hover">
                        <!-- CARD 2 -->
                        <div class="counter">
                            <div class="counter-title">
                                total size
                            </div>
                            <div class="counter-info">
                                <div id="TotalSizeCard" class="counter-count">
                                    Size
                                </div>
                                <i class='bx bx-memory-card'></i>
                            </div>
                        </div>
                        <!-- END CARD 2 -->
                    </div>
                </div>
                <div class="col-3 col-md-6 col-sm-12">
                    <div class="counter-card box-hover">
                        <!-- CARD 3 -->
                        <div class="counter">
                            <div class="counter-title">
                                total actions
                            </div>
                            <div class="counter-info">
                                <div id="TotalActionsCard" class="counter-count">
                                    Actions
                                </div>
                                <i class='bx bx-sort'></i>
                            </div>
                        </div>
                        <!-- END CARD 3 -->
                    </div>
                </div>
                <div class="col-3 col-md-6 col-sm-12">
                    <div class="counter-card box-hover">
                        <!-- CARD 4 -->
                        <div class="counter">
                            <div class="counter-title">
                                total users
                            </div>
                            <div class="counter-info">
                                <div id="TotalUsersCard" class="counter-count">
                                    Users
                                </div>
                                <i class='bx bx-user'></i>
                            </div>
                        </div>
                        <!-- END CARD 4 -->
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-3 col-md-6 col-sm-12">
                    <!-- BUCKETS TABLE -->

                    <div class="box">
                        <div class="box-header">
                            Buckets
                        </div>
                        <div class="box-body overflow-scroll">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Bucket</th>
                                        <th style="text-align: center;">Files</th>
                                        <th style="text-align: right;">Size</th>
                                    </tr>
                                </thead>
                                <tbody id="BucketTable">
                                    <tr>
                                        <td> <i class='bx bx-folder'></i> Bucket</td>
                                        <td style="text-align: center;">Files</td>
                                        <td style="text-align: right;">Size</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- END BUCKETS TABLE -->
                </div>

                <div class="col-9 col-md-6 col-sm-12">
                    <!-- FILES TABLE -->

                    <div class="box">
                        <div id="FileHeader" class="box-header">
                            Files
                        </div>
                        <div class="box-body overflow-scroll">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="text-align: left;">File</th>
                                        <th style="text-align: center;">Size</th>
                                        <th style="text-align: center;">Date</th>
                                        <th style="text-align: center;">Download</th>
                                        <th style="text-align: center;">Delete</th>
                                        <th style="text-align: right;">ID</th>
                                    </tr>
                                </thead>
                                <tbody id="FileTable">
                                    <tr>
                                        <td style="text-align: left;">File</td>
                                        <td style="text-align: center;">Size</td>
                                        <td style="text-align: center;">Date</td>
                                        <td style="text-align: center;">Download</td>
                                        <td style="text-align: center;">Delete</td>
                                        <td style="text-align: right;">ID</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- END FILES TABLE -->
                </div>
            </div>
        </div>
    </div>
    <!-- END MAIN CONTENT -->

    <div class="overlay"></div>

    <!-- SCRIPT -->
    <!-- APEX CHART -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <!-- APP JS -->
    <script src="./js/app.js"></script>

</body>

</html>