<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>

<body
	style="background-color: #222533; padding: 20px; font-size: 14px; line-height: 1.43; font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, sans-serif;">
	<style>
		@media only screen and (max-width: 600px) {
			.inner-body {
				width: 100% !important;
			}

			.footer {
				width: 100% !important;
			}
		}

		@media only screen and (max-width: 500px) {
			.button {
				width: 100% !important;
			}
		}

	</style>
	<div style="max-width: 600px; margin: 0 auto; background-color: #fff; box-shadow: 0 20px 50px rgba(0,0,0,0.05);">
		<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
			<tr>
				<td align="center">
					<table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
						{{ $header ?? '' }}

						<!-- Email Body -->
						<tr>
							<td class="body" width="100%" cellpadding="0" cellspacing="0">
								<table class="inner-body" align="center" cellpadding="0" cellspacing="0" role="presentation">
									<!-- Body content -->
									<tr>
										<td class="content-cell">
											<div style="padding: 60px 70px;">

												{{ Illuminate\Mail\Markdown::parse($slot) }}

												{{ $subcopy ?? '' }}

												<h4 style="margin-bottom: 10px;">Need Help?</h4>
												<div style="color: #A5A5A5; font-size: 12px;">
													<p>For more enquiries, call 01000110090</p>
												</div>

												<div style="color: #636363; font-size: 14px;">Live your best life with Capital X Card.</div>
												<div style="color: #636363; font-size: 14px;">Your friends at Capital X.</div>

											</div>
										</td>
									</tr>
								</table>
							</td>
						</tr>


						{{ $footer ?? '' }}
					</table>
				</td>
			</tr>
		</table>

	</div>

</body>

</html>
