<?php

$curl = curl_init();
$accountTag="";
$emailAccount="";
$Key="";
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.cloudflare.com/client/v4/graphql',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{"operationName":"GetHeadlineStats","variables":{"accountTag":"$accountTag","filter":{"datetime_geq":"2022-08-22T00:00:00Z","datetime_leq":"2022-08-28T00:00:00Z"},"previousPeriodFilter":{"datetime_geq":"2022-08-13T09:36:00Z","datetime_leq":"2022-08-20T09:36:00Z"},"encryptedFilter":{"clientSSLProtocol_neq":"none"},"fourxxFilter":{"edgeResponseStatus_geq":400,"edgeResponseStatus_leq":499},"fivexxFilter":{"edgeResponseStatus_geq":500,"edgeResponseStatus_leq":599}},"query":"query GetHeadlineStats {\\n  viewer {\\n    accounts(filter: {accountTag: $accountTag}) {\\n      statsOverTime: httpRequestsOverviewAdaptiveGroups(filter: $filter, limit: 2000) {\\n        sum {\\n          requests\\n          bytes\\n          pageViews\\n          cachedRequests\\n          cachedBytes\\n          visits\\n          __typename\\n        }\\n        dimensions {\\n          timestamp: date\\n          __typename\\n        }\\n        __typename\\n      }\\n      encryptedRequestsOverTime: httpRequestsOverviewAdaptiveGroups(filter: {AND: [$encryptedFilter, $filter]}, limit: 2000) {\\n        sum {\\n          requests\\n          bytes\\n          __typename\\n        }\\n        dimensions {\\n          timestamp: date\\n          __typename\\n        }\\n        __typename\\n      }\\n      fourxxOverTime: httpRequestsOverviewAdaptiveGroups(filter: {AND: [$fourxxFilter, $filter]}, limit: 2000) {\\n        sum {\\n          requests\\n          __typename\\n        }\\n        dimensions {\\n          timestamp: date\\n          __typename\\n        }\\n        __typename\\n      }\\n      fivexxOverTime: httpRequestsOverviewAdaptiveGroups(filter: {AND: [$fivexxFilter, $filter]}, limit: 2000) {\\n        sum {\\n          requests\\n          __typename\\n        }\\n        dimensions {\\n          timestamp: date\\n          __typename\\n        }\\n        __typename\\n      }\\n      deltas: httpRequestsOverviewAdaptiveGroups(filter: $previousPeriodFilter, limit: 1) {\\n        sum {\\n          requests\\n          bytes\\n          cachedRequests\\n          cachedBytes\\n          pageViews\\n          visits\\n          __typename\\n        }\\n        __typename\\n      }\\n      encryptedDeltas: httpRequestsOverviewAdaptiveGroups(filter: {AND: [$encryptedFilter, $previousPeriodFilter]}, limit: 1) {\\n        sum {\\n          requests\\n          bytes\\n          __typename\\n        }\\n        __typename\\n      }\\n      fourxxDeltas: httpRequestsOverviewAdaptiveGroups(filter: {AND: [$fourxxFilter, $previousPeriodFilter]}, limit: 1) {\\n        sum {\\n          requests\\n          __typename\\n        }\\n        __typename\\n      }\\n      fivexxDeltas: httpRequestsOverviewAdaptiveGroups(filter: {AND: [$fivexxFilter, $previousPeriodFilter]}, limit: 1) {\\n        sum {\\n          requests\\n          __typename\\n        }\\n        __typename\\n      }\\n      __typename\\n    }\\n    __typename\\n  }\\n}\\n"}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'X-Auth-Email:'.$emailAccount,
    'X-Auth-Key:'.$Key,
  ),
));

$response = curl_exec($curl);
curl_close($curl);
curl_reset($curl);
$data=json_decode($response,true);

$curls = curl_init();
curl_setopt($curls,  CURLOPT_URL , 'https://api.cloudflare.com/client/v4/graphql');
curl_setopt($curls,  CURLOPT_RETURNTRANSFER , true);
curl_setopt($curls,  CURLOPT_ENCODING , '');
curl_setopt($curls,  CURLOPT_MAXREDIRS , 10);
curl_setopt($curls,  CURLOPT_TIMEOUT , 0);
curl_setopt($curls,  CURLOPT_FOLLOWLOCATION , true);
curl_setopt($curls,  CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1);
curl_setopt($curls,  CURLOPT_CUSTOMREQUEST , 'POST');
curl_setopt($curls,  CURLOPT_POSTFIELDS , '{"operationName":"GetLocations","variables":{"accountTag":"$accountTag","filter":{"datetime_geq":"2022-08-20T10:28:00Z","datetime_leq":"2022-08-27T10:28:00Z"}},"query":"query GetLocations {\n  viewer {\n    accounts(filter: {accountTag: $accountTag}) {\n      locationTotals: httpRequestsOverviewAdaptiveGroups(filter: $filter, limit: 1000, orderBy: [sum_requests_DESC]) {\n        sum {\n          requests\n          bytes\n          __typename\n        }\n        dimensions {\n          clientCountryName\n          __typename\n        }\n        __typename\n      }\n      __typename\n    }\n    __typename\n  }\n}\n"}');
curl_setopt($curls,  CURLOPT_HTTPHEADER ,[
  'Content-Type: application/json',
    'X-Auth-Email:'.$emailAccount,
    'X-Auth-Key:'.$Key,
]);



$response_location = curl_exec($curls);
curl_close($curls);
$response_location=json_decode($response_location,true);
$locations=[];
foreach ($response_location['data']['viewer']['accounts'][0]['locationTotals'] as $key => $value) {
 
  $locations[$value['dimensions']['clientCountryName']]=$value['sum']['requests'];
}
dd( $locations);
function dd($data){
  highlight_string("<?php\n " . var_export($data, true) . "?>");
  echo '<script>document.getElementsByTagName("code")[0].getElementsByTagName("span")[1].remove() ;document.getElementsByTagName("code")[0].getElementsByTagName("span")[document.getElementsByTagName("code")[0].getElementsByTagName("span").length - 1].remove() ; </script>';
  die();
}

$viewer=$data['data']['viewer']['accounts'][0]['deltas'][0]['sum'];

//dd($viewer);
$resualt=[];
foreach ($data['data']['viewer']['accounts'][0]['statsOverTime'] as $key => $value) {
  $resualt[]= ["timestamp"=>$value['dimensions']['timestamp'],
  "visits"=>$value['sum']['visits'],
  "requests"=>$value['sum']['requests'],
  "pageViews"=>$value['sum']['pageViews'],
];

}
$timestamp = array_column($resualt, 'timestamp');

array_multisort($timestamp, SORT_ASC, $resualt);
// dd($resualt);
?>





<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  

<div style="width:40%;float:left;">
<canvas id="VisistorPages"  width="100%" height="300"></canvas>
</div>
<div style="width:40%;float:left;">
<canvas id="pageViews"  width="100%" height="300"></canvas>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js" integrity="sha512-sW/w8s4RWTdFFSduOTGtk4isV1+190E/GghVffMA9XczdJ2MDzSzLEubKAs5h0wzgSJOQTRYyaz73L3d6RtJSg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
  
$(document).ready(function(){

var ctx = document.getElementById('VisistorPages').getContext('2d');
    var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_values(array_column($resualt,'timestamp')))?>,
        datasets: [
        {
            fill:'start',
            label: '#  الزيارات ',
            data: <?php echo json_encode(array_values(array_column($resualt,'visits')))?>,
            backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
            ],
            borderColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
              
            ],
            borderWidth: 1
        }
    ]
    },
    options: {
        elements:{
            line:{
                tension:0.4
            }
        },  
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
      filler: {
        propagate: false,
      },
      title: {
        display: true,
        text: (ctx) => 'احصائيات الموقع'
      }
    },
    interaction: {
      intersect: false,
    }
}
});



var ctx = document.getElementById('pageViews').getContext('2d');
    var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_values(array_column($resualt,'timestamp')))?>,
        datasets: [
        {
            fill:'start',
            label: '#  الصفحات ',
            data: <?php echo json_encode(array_values(array_column($resualt,'pageViews')))?>,
            backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
            ],
            borderColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
              
            ],
            borderWidth: 1
        }
    ]
    },
    options: {
        elements:{
            line:{
                tension:0.4
            }
        },  
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
      filler: {
        propagate: false,
      },
      title: {
        display: true,
        text: (ctx) => 'احصائيات الموقع'
      }
    },
    interaction: {
      intersect: false,
    }
}
});
});


</script>
</body>
</html>
