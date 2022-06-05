<?php
require_once __DIR__ ."/vendor/autoload.php";
$collection = (new MongoDB\Client)->lab->lab;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab2</title>
    <style>
        .container {
        background-color: lightblue;
        }
    </style>
    <script>
        function form1() {
            let group = document.getElementById("group").value;
            let result = localStorage.getItem(group);
            document.getElementById('table').innerHTML = null;
            document.getElementById('localStorage').innerHTML = result; 
        }
        function form2() {
            let teacher = document.getElementById("teacher").value;
            let disciple = document.getElementById("disciple").value;
            let key = teacher + "&" + disciple; 
            let result = localStorage.getItem(key);
            document.getElementById('table').innerHTML = null;
            document.getElementById('localStorage').innerHTML = result;
        }
        function form3(){
            let auditorium = document.getElementById("auditorium").value;
            let result = localStorage.getItem(auditorium);
            document.getElementById('table').innerHTML = null;
            document.getElementById('localStorage').innerHTML = result;
        }
    </script>
</head>
<body>
<p><strong>Мирошниченко Алина, КИУКИу-20-2, Лабораторная №2, Вариант 1<strong>
<form method="get" action="">
        <p>Вывести расписание лабораторных работ группы <select name="group" id="group" onchange="form1()">
            <?php
                $group = $collection->distinct("group");
                foreach ($group as $document) {
                    echo "<option>$document</option>";
                }
            ?>
            </select>
        <button>ОК</button>
    </form>

    <form method="get" action="">
        <p>Вывести расписание занятий преподавателя <select name="teacher" id="teacher" onchange="form2()">
            <?php
                $group = $collection->distinct("teacher");
                foreach ($group as $document) {
                    echo "<option>$document</option>";
                }
            ?>
                </select>
        c дисциплиной<select name="disciple" id="disciple" onchange = form2()()>
            <?php
                $group = $collection->distinct("disciple");
                foreach ($group as $document) {
                    echo "<option>$document</option>";
                }    
            ?>
            </select>
        <button>ОК</button>
    </form>

    <form method="get" action="">
        <!--Занятие аудитории-->
        <p>Вывести расписание аудитории <select name="auditorium" id="auditorium" onchange="form3()">
            <?php
                $auditorium = $collection->distinct("auditorium");
                foreach ($auditorium as $document) {
                    echo "<option>$document</option>";
                }
                ?>
                </select>
            <button>ОК</button>
        </form>

    <?php
        /* Первый запрос */
        if (isset($_REQUEST['group'])) {
            $group = $_REQUEST['group'];
            $type = 'Laboratory';
            $cursor = $collection->find(
                [
                    'group' => $group,
                    'type' => $type
                ]
            );
            $result = "<table border=1 id=table><tr><th>Group</th><th>Day</th><th>Date</th><th>Number</th><th>Auditorium</th><th>Disciple</th><th>Type</th><th>Teacher</th></tr>";
            foreach ($cursor as $document) {

                $day = $document['day'];
                $date = $document['date'];
                $number = $document['number'];
                $auditorium = $document['auditorium'];
                $disciple =  $document['disciple'];
                $teacher = $document['teacher'];
                if (is_object($teacher)) {
                    $teacher = (array)$teacher;
                    $teacher = (implode('</br> ', $teacher));
                }
                $result = $result . "<tr><td>$group</td><td>$day</td><td>$date</td><td>$number</td><td>$auditorium</td><td>$disciple</td><td>$type</td><td>$teacher</td></tr>";
                echo "<script> localStorage.setItem('$group', '$result'); </script>";
            }
            echo $result;

        }/* Второй запрос */
        if (isset($_REQUEST['teacher']) && isset($_REQUEST['disciple'])) {
            $teacher = $_REQUEST['teacher'];
            $disciple = $_REQUEST['disciple'];
            $cursor = $collection->find(
                [
                    'teacher' => $teacher,
                    'disciple' => $disciple
                ]
            );
            $key = $teacher."&".$disciple;
            $result = "<table border=1 id=table><tr><th>Group</th><th>Day</th><th>Date</th><th>Number</th><th>Auditorium</th><th>Disciple</th><th>Type</th><th>Teacher</th></tr>";
            foreach ($cursor as $document) {
                $date = $document['date'];
                $group = $document['group'];
                $day = $document['day'];
                $number = $document['number'];
                $auditorium = $document['auditorium'];
                $type = $document['type'];
            
            if (is_object($group)) {
                $group = (array)$group;
                $group = (implode('</br>', $group));
            }
            $result = $result . "<tr><td>$group</td><td>$day</td><td>$date</td><td>$number</td><td>$auditorium</td><td>$disciple</td><td>$type</td><td>$teacher</td></tr>"; 
        }
        echo $result;
        echo "<script> localStorage.setItem('$key', '$result'); </script>";
        }

        /* третий запрос */   
        if (isset($_REQUEST['auditorium'])) {
            $auditorium = $_REQUEST['auditorium'];
            $cursor = $collection->find(['auditorium' => $auditorium]
            );
            $result = "<table border=1 id=table><tr><th>Group</th><th>Day</th><th>Date</th><th>Number</th><th>Auditorium</th><th>Disciple</th><th>Type</th><th>Teacher</th></tr>";
            foreach ($cursor as $document) {
            $group = $document['group'];
            $date = $document['date'];
            $day = $document['day'];
            $number = $document['number'];
            $teacher = $document['teacher'];
            $disciple = $document['disciple'];
            $type = $document['type'];
            if (is_object($group)) {
                $group = (array)$group;
                $group = (implode('</br>', $group));
            }

            if (is_object($teacher)) {
                $teacher = (array)$teacher;
                $teacher = (implode('</br>', $teacher));
            }
            $result = $result . "<tr><td>$group</td><td>$day</td><td>$date</td><td>$number</td><td>$auditorium</td><td>$disciple</td><td>$type</td><td>$teacher</td></tr>"; 
        }
        echo $result;
        echo "<script> localStorage.setItem('$auditorium', '$result'); </script>";
    }
    ?>
    <p>Результат работы. Local Storage подсвечивается синим<p>
<div id="localStorage" class="container"></div>
</body>
</html>