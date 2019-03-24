<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

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
			input.error{
				border:1px solid red;
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
			
			table tbody *,table tfoot *{
				text-align: center;
			}
        </style>
    </head>
    <body>
			<div class="container">
        {{Form::open(array('id'=>'add_product'))}}
					<h1>Add Product </h1>
					<div class="form-group name">
						{{Form::label('Product Name')}}
						{{Form::text('name',null,array('id'=>'name'))}}
					</div>
					<div class="form-group quantity">
						{{Form::label('Product Quantity')}}
						{{Form::text('quantity',null,array('id'=>'quantity'))}}
					</div>
					<div class="form-group price">
						{{Form::label('Product Price')}}
						{{Form::text('price',null,array('id'=>'price'))}}
					</div>
					{{Form::submit('Add!')}}
				{{Form::close()}}
			</div>
			<table width="100%" id="products_table">
				<thead>
					<th>Product Name</th>
					<th>Product Quantity</th>
					<th>Product Price</th>
					<th>Product Created Time</th>
					<th>Total</th>
				</thead>
				<tbody>
					
				</tbody>
				<tfoot>
					<th>Summary</th>
					<th></th>
					<th></th>
					<th></th>
					<th id="total_amount"></th>
				</tfoot>
			</table>
    </body>
	
	<script>
		var add_product_form=document.getElementById('add_product');
		add_product_form.addEventListener('submit',function(e){
			
			var inputs = add_product_form.getElementsByTagName('input');
			for(var i=0;i<inputs.length;i++){
				inputs[i].classList.remove('error');
			}
			e.preventDefault();
			var request=new XMLHttpRequest();
			request.responseType = 'json';
			var formData = new FormData(add_product_form);


			request.open('post',"{{url('/add_product')}}");
			request.send(formData); 
			request.onload = function() {

				if(request.response.result == 0){
					for(k in request.response.message){
						document.getElementById(request.response.message[k].name).classList.add('error');
					}
				}else{
					load_table()
				}
			}

		})
		
		function load_table(){
			document.getElementById('products_table').getElementsByTagName('tbody')[0].textContent = '';
			var formData = new FormData();
			formData.append("_token", "{{csrf_token()}}");
			var request=new XMLHttpRequest();
			request.responseType = 'json';
			

			request.open('post',"{{url('/products/fetch')}}");
			request.send(formData); 
			request.onload = function() {
				var table = document.getElementById('products_table').getElementsByTagName('tbody')[0];
				var data = request.response;
				
				var total = 0;
				for(var i=0;i<data.length;i++){
					console.log(data[i].name)
					var newRow = document.createElement('tr');
					
					var name_column = document.createElement('td');
					name_column.textContent = data[i].name;
					newRow.appendChild(name_column); 
					
					var quantity_column = document.createElement('td');
					quantity_column.textContent = data[i].quantity;
					newRow.appendChild(quantity_column); 
					
					var price_column = document.createElement('td');
					price_column.textContent = data[i].price;
					newRow.appendChild(price_column); 
					
					var created_column = document.createElement('td');
					created_column.textContent = data[i].created_at;
					newRow.appendChild(created_column); 
					
					var total_column = document.createElement('td');
					total_column.textContent = data[i].quantity*data[i].price;
					newRow.appendChild(total_column); 
					
					total += data[i].quantity*data[i].price;
					table.appendChild(newRow)
				}
				
				document.getElementById('total_amount').textContent = total;
			}
		}
		
		load_table()
	</script>
</html>
