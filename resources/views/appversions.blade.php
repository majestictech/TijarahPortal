<!DOCTYPE html>
<html lang="">
    <head>
      
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center ">
           

            <div class="content">
                <div class="title m-b-md">
                 <img src="http://localhost/posadmin/public/assets/images/tijarah-logo.png" width="180" alt="">
                </div>
				<table class="table mb-0 table-striped table-bordered" id="myTable">
				
					<thead>
						<tr>
							<th scope="col" style="font:20px;">{{ __('lang.appVersion')}}</th>
							
						</tr>
					</thead>
					<tbody>
						@foreach($results as $key=>$result)
						<tr>
							<td style="padding:20px;"><a href="{{$result->appUpdateFiles}}">{{ $result->appfile }} </a></td>
							
						</tr>
						@endforeach
                        
						
					</tbody>
				</table>

               
            </div>
        </div>
    </body>
	<script>

	</script>
</html>
