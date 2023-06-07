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
    use Appwrite\Query;
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
        $result2 = $storage->listBuckets(
            [
                Query::orderAsc("name")
            ]
        );

        $result3 = json_encode($result2);
        print_r($result3);


    }
    ?>

    <!-- END PHP SCRIPTS -->


    <!-- SCRIPTS -->
    <script>
        const { Client, Account, ID, Storage, Query, Databases } = Appwrite;

        let FileExtArray = [];
        let done = [];
        let BucketIDArray = [];

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
                ListBuckets();
                GetActions();
                LoopRequests();

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


        function ListBuckets() {

            var BucketArrayPHP = JSON.stringify(<?php GetBuckets(); ?>);
            var BucketArray = JSON.parse(BucketArrayPHP);
            let i = 0;
            let total = 0;
            let totalsize = 0;


            while (i < BucketArray.total) {
                let BucketName = BucketArray.buckets[i].name;
                let BucketID = BucketArray.buckets[i].$id;
                BucketIDArray.push(BucketID);

                const client = new Client()
                    .setEndpoint('http://51.161.212.158:9191/v1') // Your API Endpoint
                    .setProject('64511dda13070874dfb6');               // Your project ID

                const storage = new Storage(client);

                storage.listFiles(BucketID, [Query.orderAsc("name")]).then(function (response) {
                    let x = 0;
                    let size = 0;
                    let sizeunit = "|MB";
                    total = total + response.files.length;
                    document.getElementById('TotalFilesCard').innerHTML = total;

                    while (x <= response.files.length - 1) {
                        size = size + (response.files[x].sizeOriginal) / 1000000;
                        totalsize = totalsize + (response.files[x].sizeOriginal);
                        document.getElementById('TotalSizeCard').innerHTML = (totalsize / 1000000).toFixed(2) + sizeunit;
                        sizeunit = "|MB";
                        x = x + 1;
                    }

                    console.log("  => Get Buckets: Success"); // Success

                }, function (error) {
                    console.log("  => Get Buckets: FAILED -> | " + error);
                });



                i = i + 1;
            }
            console.log("List | " + BucketIDArray)
            //GetFiles(); //Should be uncommented when completing below TODO:
        }

        //TODO: Compile all files in all buckets into One array and then pouplate the pie chart and Large File list.

        /*function GetFiles() {
            const client = new Client()
                .setEndpoint('http://51.161.212.158:9191/v1') // Your API Endpoint
                .setProject('64511dda13070874dfb6'); // Your project ID

            const storage = new Storage(client);
            let i = 0;
            let y = 0;
            let x = 0;
            document.getElementById('LargeFiles').innerHTML = '';
            let FileArray = [];
            let TempFileArray = [];

            while (i < BucketIDArray.length) {
                storage.listFiles(BucketIDArray[i]).then(function (response) {
                    TempFileArray = response.files;
                    console.log(TempFileArray + " | " + BucketIDArray[i])
                    FileArray.push(TempFileArray);

                    console.log("  => Get LargeFiles: Success"); // Success



                }, function (error) {
                    console.log("  => Get LargeFiles: FAILED -> | " + error);
                });

                i = i + 1;
                x = x + 1;
            }
            

            while (x < FileArray.length) {
                let FileName = FileArray[x].name;
                let FileSize = 0;
                let FileExt = "NULL";
                let SizeUnit = "NULL";

                if (Math.trunc(FileArray[x].sizeOriginal / 1000000) === 0) {
                    FileSize = (FileArray[x].sizeOriginal / 1000000) * 1000;
                    SizeUnit = "|KB";
                }
                else if (Math.trunc(FileArray[x].sizeOriginal / 1000000) === 1) {
                    FileSize = (FileArray[x].sizeOriginal / 1000000);
                    SizeUnit = "|MB";
                }


                setTimeout(() => {


                    let extensions = ['exe', 'png', 'pdf', 'txt'];
                    for (let j = 0; j < extensions.length; j++) {

                        if (FileName.includes("." + extensions[j])) {

                            if (!done.includes("." + extensions[j])) {
                                FileExtArray.push(0); // add a new index with the value of 0
                                done.push("." + extensions[j]);
                                FileExtArray[j] = FileExtArray[j] + 1;
                                FileExt = extensions[j];
                                console.log("New Ext")

                            }
                            else if (done.includes("." + extensions[j])) {
                                FileExtArray[j] = FileExtArray[j] + 1;
                                FileExt = extensions[j];
                                break;
                            }
                            console.log(done)
                            console.log(FileExtArray)
                        }

                    }
                    MakeExtChart();
                }, 500);

                if (y < 6) {
                    document.getElementById('LargeFiles').insertAdjacentHTML('beforeend', '<li class="LargeFile-list-item"> <div class="item-info"> <div class="item-name"> <div class="LargeFile-name">' + FileName + '</div> <div class="text-second">' + FileExt + '</div> </div> </div> <div class="item-sale-info"> <div class="text-second">size</div> <div class="LargeFile-size">' + FileSize.toFixed(2) + SizeUnit + '</div> </div> </li>');
                }
                y = y + 1;
                x = x + 1;
            }

        }*/

        function MakeExtChart() {
            let extension_options = {
                series: FileExtArray,
                labels: done,
                chart: {
                    height: 350,
                    type: 'donut',
                    width: 340,
                },
                dataLabels: {
                    enabled: true,

                },
                colors: ['#6ab04c', '#2980b9', '#f39c12', '#d35400']
            }

            let extension_chart = new ApexCharts(document.querySelector("#extension-chart"), extension_options)
            extension_chart.render()


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
                    Query.select(['File', 'User', 'Date', 'Action', 'Response', 'Source']),
                    Query.orderDesc("$createdAt")
                ]
            ).then(function (response) {
                let i = 0;
                let ActionArray = response.documents;
                ActionCount = ActionArray.length;

                document.getElementById('ActionsTable').innerHTML = '';
                while (i <= ActionArray.length - 1 && i < 10) {
                    let File = ActionArray[i].File;
                    let User = ActionArray[i].User;
                    let Date = ActionArray[i].Date;
                    let Action = ActionArray[i].Action;
                    let Response = ActionArray[i].Response;
                    let Source = ActionArray[i].Source;

                    document.getElementById('ActionsTable').insertAdjacentHTML('beforeend',
                        (`<tr>
                                        <td>` + File + `</td>
                                        <td>
                                            <div class="order-owner">
                                                <img src="./images/user-image-2.png" alt="user image">
                                                <span>` + User + `</span>
                                            </div>
                                        </td>
                                        <td>` + Date + `</td>
                                        <td>
                                            <span class="action-tag ` + Action + `">
                                                ` + Action + `
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-response ` + Response + `">
                                                <div class="dot"></div>
                                                <span>` + Response + `</span>
                                            </div>
                                        </td>
                                        <td>` + Source + `</td>
                                    </tr>`)
                    );
                    i = i + 1
                }

                document.getElementById('TotalActionsCard').innerHTML = ActionCount;
                console.log("  => Get Actions: Success"); // Success


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

                    setTimeout(() => {
                        ActionLogArray[(7 - i)] = response.total;
                        console.log('     ->', response.total, isoDate, i, object);
                        DateArray[(7 - i)] = dayOfWeek;
                    }, 500);



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

        function MakeReqChart() {
            let request_options = {
                series: [{
                    data: ActionLogArray,
                }],
                colors: ['#6ab04c'],

                chart: {
                    height: 350,
                    type: 'area',
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',

                },

                xaxis: {
                    type: 'day',
                    categories: DateArray,
                },

                legend: {

                    position: 'top',
                }
            }

            let request_chart = new ApexCharts(document.querySelector("#request-chart"), request_options)

            setTimeout(() => {
                request_chart.render()
                console.log('  => Chart Render: Success')
            }, 1000);

            setTimeout(() => {
                console.log("[!] => END <= [!]");
                RequestDivider();
            }, 1000);
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
                <a href="#" class="active">
                    <i class='bx bx-home'></i>
                    <span>dashboard</span>
                </a>
            </li>


            <li>
                <a href="./Files.php">
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
                dashboard
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
                    <!-- LARGE FILES -->
                    <div class="box f-height">
                        <div class="box-header">
                            largest files
                        </div>
                        <div class="box-body">
                            <ul id="LargeFiles" class="LargeFile-list">
                                <li class="LargeFile-list-item">
                                    <div class="item-info">

                                        <div class="item-name">
                                            <div class="LargeFile-name">FileName</div>
                                            <div class="text-second">FileType</div>
                                        </div>
                                    </div>
                                    <div class="item-sale-info">
                                        <div class="text-second">size</div>
                                        <div class="LargeFile-sales">FileSize</div>
                                    </div>
                                </li>


                            </ul>
                        </div>
                    </div>
                    <!-- LARGE FILES -->
                </div>
                <div class="col-4 col-md-6 col-sm-12">
                    <!-- EXTENSION CHART -->
                    <div class="box f-height">
                        <div class="box-header">
                            File Extensions
                        </div>
                        <div class="box-body">
                            <div id="extension-chart"></div>
                        </div>
                    </div>
                    <!-- END EXTENSION CHART -->
                </div>
                <div class="col-5 col-md-12 col-sm-12">
                    <!-- USAGE CHART -->
                    <div class="box f-height">
                        <div class="box-header">
                            API Requests
                        </div>
                        <div class="box-body">
                            <div id="request-chart"></div>
                        </div>
                    </div>
                    <!-- END USAGE CHART -->
                </div>
                <div class="col-12">
                    <!-- ACTIONS TABLE -->
                    <div class="box">
                        <div class="box-header">
                            recent actions
                        </div>
                        <div class="box-body overflow-scroll">
                            <table>
                                <thead>
                                    <tr>
                                        <th>File</th>
                                        <th>User</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                        <th>Response</th>
                                        <th>Source IP</th>
                                    </tr>
                                </thead>
                                <tbody id="ActionsTable">
                                    <tr>
                                        <td>FileName</td>
                                        <td>
                                            <div class="order-owner">
                                                <img src="./images/user-image-2.png" alt="user image">
                                                <span>UserName</span>
                                            </div>
                                        </td>
                                        <td>2023-05-05</td>
                                        <td>
                                            <span class="action-tag download">
                                                Action
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-response success">
                                                <div class="dot"></div>
                                                <span>Response</span>
                                            </div>
                                        </td>
                                        <td>IP Address</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- END ACTIONS TABLE -->
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