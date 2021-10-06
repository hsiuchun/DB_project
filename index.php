<?php
ini_set("display_errors","On");
error_reporting(E_ALL);

#include("conn.php");
$link=mysqli_connect("localhost","root","Rinee252515","db_practice")
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A1073316_Checkpoint6</title>
    <link rel="stylesheet" href="all.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <script src="./js/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script src="./js/jquery.horizontalmenu.js"></script>
    <script>
        $(function () {
            $('.ah-tab-wrapper').horizontalmenu({
                itemClick : function(item) {
                    $('.ah-tab-content-wrapper .ah-tab-content').removeAttr('data-ah-tab-active');
                    $('.ah-tab-content-wrapper .ah-tab-content:eq(' + $(item).index() + ')').attr('data-ah-tab-active', 'true');
                    return false; //if this finction return true then will be executed http request
                }
            });
        });
    </script>
</head>
<body>
    <div class="header">
        <div id="name">D e t a i l O f C h a r t e r T r i p </div>
    </div>
    <div class="content"> 
        <div class="main">
            <div class="search d1">
                <form action="" method="post">
                    <table style="border:0px;"><tr><td style="border:0px;">
                    <select name="forwhat" style="height:42px; background-color:transparent; border:0px; color:#dddfe6; font-size:16px; outline:none;">
                        <option value="charter_trip" style="color:black; border:0px; outline:none; background-color:transparent;">航班編號</option>
                        <option value="employees" style="color:black; border:0px; outline:none; background-color:transparent;">員工編號</option>
                    </select></td><td style="border:0px;">
                    <input type="text" name="keyword" placeholder="請輸入關鍵字...">
                    <button type="submit"></button></td></tr></table>
                </form>
            </div>
            <div class="ah-tab-wrapper">
                <div class="ah-tab">
                    <a class="ah-tab-item" data-ah-tab-active="true" href="">航行總覽</a>
                    <a class="ah-tab-item" href="">員工總覽</a>
                    <a class="ah-tab-item" href="">飛機總覽</a>
                </div>
            </div>
            <div class="ah-tab-content-wrapper">
            <?php
                if(isset($_POST["forwhat"])){
                    $forwhat=$_POST["forwhat"];
                }else{
                    $forwhat="";
                }    
                if($forwhat=="charter_trip"){
                    $cnumber=$_POST["keyword"];
                }else{ 
                    $cnumber="";
                }    
                if($forwhat=="employees"){
                    $enumber=$_POST["keyword"];
                }else{
                    $enumber="";
                }
                //echo $forwhat;
                //echo $cnumber;
                //echo $enumber;
            ?>
                <div class="ah-tab-content" data-ah-tab-active="true">
                    <?php
                        $SQL="SELECT * FROM charter_trip C1,customer C2 WHERE C1.ssn=C2.ssn";
                        if(!empty($cnumber)){
                            $SQL.=" AND C1.ch_id='".$cnumber.'\'';
                        }
                        //echo $SQL."</br>";    
                        if($result=mysqli_query($link,$SQL)){
                            echo "<table cellpadding=10 style='text-align:center;'><tr style='font-family:PingFangSC-Regular, Verdana, Arial, 微软雅黑, 宋体'>";
                            echo "<th bgcolor='#587498'><font color=#dddfe6>顧客姓名</font></th>";
                            echo "<th bgcolor='#587498'><font color=#dddfe6>航班編號</font></th>";
                            echo "<th bgcolor='#587498'><font color=#dddfe6>飛行總距離</font></th>";
                            echo "<th bgcolor='#587498'><font color=#dddfe6>預定時間</font></th>";
                            echo "<th bgcolor='#587498'><font color=#dddfe6>航班訊息</font></th>";
                            //echo "<th bgcolor='#587498'><font color=#dddfe6>機長編號</font></th>";
                            echo "</tr>";
                            
                            while($row = mysqli_fetch_assoc($result)){
                                echo "<tr bgcolor='transparent'>";
                                echo "<td>".$row["cname"]."</td>";
                                echo "<td>".$row["ch_id"]."</td>";
                                echo "<td>".$row["distance"]."</td>";
                                echo "<td>".$row["time"]."</td>";
                                echo "<td>".$row["c_description"]."</td>";
                                //echo "<td>".$row["pid"]."</td>";
                                echo "</tr>";
                            }
                            echo "</table>";
                        }
                    ?>
                </div>
                <div class="ah-tab-content">
                    <?php
                        $SQL="SELECT DISTINCT E.eid,E.ename,E.job_type FROM employees E";
                        if(!empty($enumber)){
                            $SQL.=" WHERE E.eid='".$enumber.'\'';
                        }
                       // $SQL.=" WHERE E.eid=E2.eid AND E2.lid=L.lid AND L.lid=L2.lid AND L2.l_t_id=L3.l_t_id ";
                        $SQL.=" GROUP BY E.eid";
                       // echo $SQL;
                        if($result=mysqli_query($link,$SQL)){
                            #echo "success";
                            echo "<table cellpadding=10 style='text-align:center;'><tr>";
                            echo "<th bgcolor='#587498'><font color=#dddfe6>員工編號</font></th>";
                            echo "<th bgcolor='#587498'><font color=#dddfe6>員工姓名</font></th>";
                            #echo "<th bgcolor='#587498'><font color=#dddfe6>工作性質</font></th>";
                            echo "<th bgcolor='#587498'><font color=#dddfe6>持有證照</font></th>";
                            echo "</tr>";
                            while($row = mysqli_fetch_assoc($result)){
                                $SQL1="SELECT L3.l_t_id FROM employees E, emp_license E2, license L, l_typeof L2,license_type L3";
                                $SQL1.=" WHERE E.eid=E2.eid AND E2.lid=L.lid AND L.lid=L2.lid AND L2.l_t_id=L3.l_t_id ";
                                $SQL1.=" AND E.eid='".$row["eid"].'\''."\"";
                                $result1=mysqli_query($link,$SQL1);

                                echo "<tr bgcolor='transparent'>";
                                echo "<td>".$row["eid"]."</td>";
                                echo "<td>".$row["ename"]."</td>";
                                #echo "<td>".$row["job_type"]."</td>";
                                echo "<td>";
                                while($row1 = mysqli_fetch_assoc($result1)){
                                    echo $row1["l_t_id"]."</br>";
                                }
                                echo "</td>";
                                echo "</tr>";
                            }   
                            echo "</table>";
                        }
                    ?>
                </div>
                <div class="ah-tab-content">
                    <?php
                        $SQL="SELECT * FROM charter_trip C, aircraft A, aircraft_type A2 ";
                        $SQL.=" WHERE C.ch_id=A.ch_id AND A.a_id=A2.a_id";
                        if(!empty($cnumber)){
                            $SQL.=" AND C.ch_id='".$cnumber.'\'';
                        }
                        if($result=mysqli_query($link,$SQL)){
                            echo "<table cellpadding=10 style='text-align:center;'><tr>";
                            echo "<th bgcolor='#587498'><font color=#dddfe6>航班編號</font></th>";
                            echo "<th bgcolor='#587498'><font color=#dddfe6>使用飛機</font></th>";
                            echo "<th bgcolor='#587498'><font color=#dddfe6>飛機型號</font></th>";
                            echo "<th bgcolor='#587498'><font color=#dddfe6>起飛重量</font></th>";
                            echo "</tr>";
                            while($row = mysqli_fetch_assoc($result)){
                                echo "<tr bgcolor='transparent'>";
                                echo "<td>".$row["ch_id"]."</td>";
                                echo "<td>".$row["a_id"]."</td>";
                                echo "<td>".$row["a_type_id"]."</td>";
                                echo "<td>".$row["tk_weight"]."</td>";
                                echo "</tr>";                                                   
                            }
                            echo "</table>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
