<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Expense {{ $expense->number }}</title>
	<style>
		.clearfix:after {
		  content: "";
		  display: table;
		  clear: both;
		}

		body {
		  position: relative;
		  max-width: 612pt;
		  height: 792pt; 
		  margin: 0 auto; 
		  background: #FFFFFF; 
		  font-family: Arial, sans-serif; 
		  font-size: 12px; 
		}

		header {
		  padding: 10px 0;
		  border-bottom: 1px solid;
		  margin-bottom: 20px;
		}

		#app-name {
		  float: left;
		  margin-top: 8px;
		  margin-bottom: 10px;
		  width: 350px;
		}

		#app-name p {
		  margin: 0;
		  word-break: normal;
		}

		#app-name a {
		  text-decoration: none;
		  color: #000000;
		}

		#expense-details {
		  text-align: right;
		  margin-top: 8px;
		  margin-bottom: 10px;
		}

		#expense-details p {
		  margin: 0;
		  word-break: normal;
		}

		h1.title {
		  font-size: 1.8em;
		  font-weight: bold;
		  margin: 0px 0px 4px 0px;
		}

		h2.title {
		  font-size: 1.4em;
		  font-weight: normal;
		  margin: 0px 0px 2px 0px;
		}

		h3.title {
		  font-weight: normal;
		  margin: 0px 0px 3px 0px;
		}

		table {
		  width: 100%;
		  border-collapse: collapse;
		  border-spacing: 0;
		  margin-bottom: 20px;
		}

		table thead th{
		  padding: 6px;
		  background: #EEEEEE;
		  border: 1px solid;
		  font-weight: normal;
		}

		table tbody td {
		  border: 1px solid;
		  padding: 4px;
		}

		table tfoot th{
		  padding: 20px 4px;
		  font-size: 1.1em;
		  font-weight: bold;
		  vertical-align: top;
		}

		footer {
		  width: 100%;
		  height: 30px;
		  position: absolute;
		  bottom: 0;
		  border-top: 1px solid;
		  padding: 8px 0;
		  text-align: center;
		}

		#print_date {
		  float: left;
		  font-weight: bold;
		}

		#url {
		  float: right;
		}

		#url a {
		  text-decoration: none;
		  color: #000000;
		  font-weight: bold;
		  font-style: italic;
		}

		.text-center {
		  text-align: center;
		}

		.text-right {
		  text-align: right;
		}

		.text-left {
		  text-align: left;
		}

		.text-justify {
		  text-align: justify;
		}
	</style>
</head>
<body>
	<header class="clearfix">
		<div id="app-name">
			<h1 class="title">{{ config('expense.info.name') }}</h1>
			<p>{{ config('expense.info.address', '') }}</p>
			<p>{{ config('expense.info.contact', '') }}</p>
		</div>
		<div id="expense-details">
			<h1 class="title">Expense Number: {{ $expense->number ?? 'N/A' }}</h1>
			<p>Date: <strong>{{ $expense->date }}</strong></p>
			<p>Currency: <strong>{{ $expense->currency }}</strong></p>
		</div>
	</header>
	<main>
		<table>
			<thead>
				<tr>
					<th width="50%">Description</th>
					<th width="10%">Quantity</th>
					<th width="20%">Unit Price</th>
					<th width="20%">Sub Total</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($expense->lines as $line)
					<tr>
						<td>{{ $line->description }}</td>
						<td class="text-center">{{ $line->quantity }}</td>
						<td class="text-right">
							{{ 
								number_format(
									$line->amount / 100, 
									config('expense.format.decimals', 2), 
									config('expense.format.decimal_separator', ','),
									config('expense.format.thousand_seperator', '.')
								) 
							}}
						</td>
						<td class="text-right">
							{{ 
								number_format(
									($line->quantity * $line->amount) / 100, 
									config('expense.format.decimals', 2), 
									config('expense.format.decimal_separator', ','),
									config('expense.format.thousand_seperator', '.')
								) 
							}}
						</td>
					</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<th class="text-left">
						@foreach ($expense->custom_fields as $field)
							{{ $field['name'] }}: {{ $field['value'] }}
							<br />
						@endforeach
					</th>
					<th colspan="2" class="text-right">
						Total Amount ({{ $expense->currency }})
					</th>
					<th class="text-right">
						{{ 
							number_format(
								$expense->amount / 100, 
								config('expense.format.decimals', 2), 
								config('expense.format.decimal_separator', ','),
								config('expense.format.thousand_seperator', '.')
							) 
						}}
					</th>
				</tr>
			</tfoot>
		</table>
	</main>
	<footer>
		<div id="print_date">Print Date: {{ now() }} / Expense ID: {{ $expense->id }}</div>
		<div id="url">
			<a href="{{ config('expense.info.url') }}">
				{{ config('expense.info.url') }}
			</a>
		</div>
	</footer>
</body>
</html>
