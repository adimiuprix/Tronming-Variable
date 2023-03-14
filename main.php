<!-- description -->
<?php echo settings('description'); ?>

<!-- keywords -->
<meta name="keywords" content="<?php echo settings('keywords'); ?>">

<!-- Social Links -->
<?php include_once __DIR__ . '/includes/social.php'; ?>

<!-- Sitename & Slogan -->
<?php echo settings('sitename'); ?>
<?php echo settings('siteslogan'); ?>

<!-- Assets -->
<?php echo themeAssets(''); ?>

<!-- base_url -->
<?php echo base_url(); ?>

<!-- Currency Name -->
<?php echo settings('currency_name'); ?>

<!-- Currency Symbol -->
<?php echo settings('currency_symbol'); ?>

<!-- Curency Code -->
<?php echo settings('currency_code');?>

<!-- Login Status Menu -->
<?php if(isset($_SESSION['username'])):?>
	Dashboard
	Account
<?php else: ?>
	Home
<?php endif; ?>

<!-- Form Login Struct -->
<form action="<?php base_url(); ?>" method="post">
	<input type="hidden" name="reference_user_id" id='reference_user_id' value="<?php echo get_cookie('ref', true); ?>">
	<input type="text" id="username" minlength="<?php echo settings('wallet_min'); ?>" maxlength="<?php echo settings('wallet_max'); ?>" pattern="[a-zA-Z0-9_-]+" name="username" placeholder="Enter Your  Address" class="form-control">
	<input type="password" id="password" minlength="4" name="password" placeholder="Enter Your Password" class="form-control">
	<button id="go_enter" onclick="return validateFormLogin();">Text</button>
</form>

<!--
	Plan Struct Start

	This is the structure of the plan section. In this part of the plan there are two models status:
	1). Home model: Used on the homepage, means when the user is not logged in if ($plan['is_default'] == 0).
	2). Models if the user is logged in, That means used on the dashboard in general ($allplans as $plan).
-->
<!--
	User = 0
	<?php foreach ($allplans as $plan): if ($plan['is_default'] == 0): ?>
	// Code Here
	<?php endif; endforeach; ?>
 -->

<!-- Users = 1 -->
<?php foreach ($allplans as $plan): ?>

	<!-- Image plan -->
	<?php echo plansAssets($plan['image']); ?>

	<!-- Plan Version -->
	<?php echo $plan['version']; ?>

	<!-- Plan Name -->
	<?php echo $plan['plan_name']; ?>

	<!-- Plan Duration -->
	<?php echo $plan['duration']; ?>

	<!-- Earning rate -->
	<?php echo $plan['point_per_day']; ?>

	<!-- Total Return -->
	<?php echo currencyFormat($plan['point_per_day'] * $plan['duration'],0) ?>

	<!-- Profit % -->
	<?php echo $plan['profit']; ?>

	<!-- Plan Duration -->
	<?php
		if ($plan['duration'] == 0) {
			echo 'forever';
		} else {
			echo "for {$plan['duration']} days";
		}
	?>
	<!-- Also you can use this code -->
	<?php echo $plan['duration']; ?>

	<?php 
		if($plan['duration'] != 0){
			echo '<li><span class="d-block">Total Profit</span>'. currencyFormat($plan['point_per_day'] * $plan['duration'],2). ' '.settings('currency_symbol').'</li>';
		}
	?>

	<!-- Affiliate Bonus % -->
	<?php echo settings('aff_comission'); ?>

	<!-- Button Buy Plan -->
	<button type="button" onclick="location.href='purchase/<?php echo $plan['id'];?>';">

	<!-- Plan Price -->
	<?php echo currencyFormat($plan['price'], 0); ?>
<?php endforeach; ?>
<!-- Plan Struct End-->

<!-- Total Users -->
<?php echo $totalUsers; ?>

<!-- Total Investments -->
<?php echo currencyFormat($totalDeposits); ?>

<!-- Total Paid -->
<?php echo currencyFormat($totalPaid); ?>

<!-- Online Days -->
<?php echo project_start_date(); ?>

<!-- FAQ Section -->
<?php foreach($faqs as $faq): ?>
	<?php echo htmlspecialchars_decode(parse_text_codes($faq['question'])); ?>	<!-- Question -->
	<?php echo htmlspecialchars_decode(parse_text_codes($faq['answer'])); ?>	<!-- Answer -->
<?php endforeach; ?>

<!-- 10 Last Withdrawal -->
<?php if($withdrawals): ?>
	<?php foreach($withdrawals as $pmt): ?>
		<?php echo $pmt['date_paid'];?>	Date
		<?php echo $pmt['amount'];?>	Amout
		<?php echo substr($pmt['username'],0,20);?>	username

		<!-- Blockchain ID Start -->
		<?php if(settings('blockchain')!=='0'){ ?>
			<a href="<?php echo blockchainUrl($pmt['tx']);?>"><?php echo substr($pmt['tx'],0,20);?><b>XXXX</b></a>
			<?php }else{ ?>
			<?php echo substr($pmt['tx'],0,20);?><b>XXXX</b>
		<?php } ?>
	<?php endforeach; ?>
		<!-- No Result -->
	<?php else: ?>
<?php endif; ?>

<!-- 10 Last Deposits -->
<?php if($deposits): ?>
	<?php foreach($deposits as $dps): ?>
		<?php echo $dps['date_paid'];?>	Date
		<?php echo $dps['amount'];?>	Amout
		<?php echo substr($dps['username'],0,20);?>	username

		<!-- Blockchain ID Start -->
		<?php if(settings('blockchain')!=='0'){ ?>
			<a href="<?php echo blockchainUrl($dps['tx']);?>" target="_blank"><?php echo substr($dps['tx'],0,20);?><b>XXXX</b></a>
		<?php }else{ ?>
		<?php echo substr($dps['tx'],0,20);?><b>XXXX</b>
		<?php } ?>
	<?php endforeach; ?>
		<!-- No Result -->
	<?php else: ?>
<?php endif; ?>

<!-- Contact Struct -->
<form action="<?php echo base_url('contact');?>" method="post" id="contactForm">
	<input type="text" id="name" name="name" required />
	<input type="email" id="email" name="email" required />
	<input type="text" id="subject" name="subject" required />
	<textarea id="message" name="message" required></textarea>
</form>

<!-- Show Address -->
<?php echo substr($_SESSION['username'], 0, 29); ?>

<!-- Balance Show -->
<input type="hidden" id="getBalance" value="<?php echo $this->userdata->balance; ?>"/>
<font id="bal"><?php echo $this->userdata->balance; ?></font>

<!--
Active Plan
Here are all your active miners, each deposit is a separate miner.
-->
<?php
	$sumCD = 0;
	$sumER = 0;
	$sumSP = 0;
	foreach ($active_plans as $key => $plans):
		$sumER += $plans->earning_rate;
		$sumSP += $plans->speed;
		$sumCD += $plans->point_per_day;
		$duration = $plans->duration;
		if ($duration == 0) {
		$leftDays = 'Unlimited';
		} else {
			$now = date_create('now');
			$end = date_add(date_create($plans->created_at), date_interval_create_from_date_string($duration . ' days'));
			$left = date_diff($now, $end);
			$leftDays = $left->days . 'd ' . $left->h . 'h ' . $left->i . 'min';
		}
?>
	<?php echo $plans->version; ?>
	<?php echo $plans->speed; ?>
	<?php echo $plans->earning_rate; ?> <?php echo settings('currency_symbol'); ?>
	<?php echo $plans->created_at; ?>
	<?php echo $leftDays; ?>
	<?php
		$sclass = $plans->status === 'active' ? 'success' : 'danger';
		echo "<span class='label label-{$sclass}'>" . ucfirst($plans->status) . "</span>";
	?>
<?php endforeach; ?>

<!-- Total Sum Plans All -->
<tr>
	<td><b>Totals</b></td>
	<td><?php echo $sumSP; ?> H/s</td>
	<td colspan="4"><?php echo currencyFormat($sumER); ?> <?php echo settings('currency_symbol'); ?> min/ <?php echo currencyFormat($sumCD); ?> <?php echo settings('currency_symbol'); ?> day</td>
</tr>

<!-- Earning Balance -->
<?php echo $this->userdata->balance;?>

<!-- Min. Withdrawal -->
<?php echo settings('min_withdraw'); ?>

<!-- Max. Withdrawal -->
<?php echo settings('max_withdraw'); ?>

<!-- Form Withdraw Struct -->
<form action="<?php echo base_url('withdrawal'); ?>" method="POST" onsubmit="return requestWithdrawal();">
	<input type="number" step="any" id="amount" name="amount" class="form-control" required>
	<input value="<?php echo $_SESSION['username'];?>" class="form-control" readonly>
	<button type="submit" id="withdrawalNow">Confirm</button>
</form>

<!-- Form Account Details -->
<form action="<?php echo base_url('account');?>" method="post" id="contactForm">
	<input type="email" id="email" name="email" placeholder="Your Email" value="<?php echo $this->userdata->email;?>" required />
	<input type="password" id="new_password" name="new_password" placeholder="Leave blank to do not change" />
	<input type="password" id="new_password_confirmation" name="new_password_confirmation" placeholder="Leave blank to do not change" />
	<input type="password" id="password" name="password" placeholder="Your current password" required />
	<button type="submit" class="btn btn-warning">Save</button>
</form>

<!--
Account History
Generally you need to use table for use this code.
-->
<!-- Referalls Arguments: Username, Date, IP Address -->
<?php
	if ($referrals) {
		foreach ($referrals as $ref) {
?>
	<?php echo substr($ref["username"], 0, 15); ?><b>xxxxx</b>
	<?php echo $ref["created_at"]; ?>
	<?php echo $ref["ip_address"]; ?>
<?php
	}} else {
?>
	<tr>
		<td colspan="3" class="text-center">No Records Found !!</td>
	</tr>
<?php
    }
?>

<!-- Commision Referalls Arguments: Date, Amount, Status -->
<?php
	if ($aff_earns) {
		foreach ($aff_earns as $aff_earn) {
?>
	<?php echo $aff_earn['date']; ?>
	<?php echo $aff_earn['amount']; ?> <?php echo settings('currency_symbol');?>
	<td>
		<?php if($aff_earn['status']==='pending'): ?>
		<span class="label label-danger"><?php echo ucfirst($aff_earn['status']); ?></span>
		<?php elseif($aff_earn['status']==='paid'): ?>
		<span class="label label-success"><?php echo ucfirst($aff_earn['status']); ?></span>
		<?php endif; ?>
	</td>
<?php
	}} else {
?>
	<tr>
		<td colspan="3" class="text-center">No Records Found !!</td>
	</tr>
<?php
    }
?>

<!-- Deposits Arguments: Date, Amount, TXID, Status -->
<?php
	if ($deposits) {
		foreach ($deposits as $deposit) {
?>
	<?php echo $deposit['created_at']; ?>
	<?php echo $deposit['amount']; ?> <?php echo settings('currency_symbol');?>
	<?php echo $deposit['tx']; ?>
	<td>
	<?php if($deposit['status']==='PENDING'): ?>
		<span class="label label-danger">Pending</span>
		<?php elseif($deposit['status']==='SUCCESS'): ?>
		<span class="label label-success">Paid</span>
		<?php else: ?>
		<span class="label label-warning"><?php echo ucfirst($deposit['status']); ?></span>
	<?php endif; ?>
	</td>
<?php
	}} else {
?>
	<tr>
		<td colspan="4" class="text-center">No Records Found !!</td>
	</tr>
<?php
    }
?>

<!-- Withdraws Arguments: Date, Amount, TXID, Status -->
<?php
	if ($withdrawals) {
		foreach ($withdrawals as $wt) {
?>
	<?php echo $wt['created_at']; ?>
	<?php echo $wt['amount']; ?> <?php echo settings('currency_symbol');?>
	<td>
		<?php if(settings('blockchain') !== '0'){ ?>
		<a href="<?php echo blockchainUrl($wt['tx']); ?>" target="_blank"><?php echo $wt['tx']; ?></a>
		<?php }else{ ?>
			<?php echo $wt['tx'];?>
		<?php } ?>
	</td>
	<td>
		<?php if($wt['status']==='PENDING'): ?><span class="bedge bedge-warning">Pending</span>
		<?php elseif($wt['status']==='SUCCESS'): ?><span class="bedge bedge-success">Paid</span>
		<?php elseif($wt['status']==='PROCESSING'): ?><span class="bedge bedge-info">Processing</span>
		<?php else: ?>
			<span class="bedge bedge-danger"><?php echo ucfirst($wt['status']); ?></span>
		<?php endif; ?>
	</td>
<?php
	}} else {
?>
	<tr>
		<td colspan="4" class="text-center">No Records Found !!</td>
	</tr>
<?php
    }
?>

<!-- Pending Purchase Arguments: Date, Amount, Status-->
<?php
	if($transactions){
		foreach ($transactions as $transaction){
	?>
	<?php echo $transaction['date']; ?>
	<?php echo $transaction['amount']; ?> <?php echo settings('currency_symbol');?>
	<td>
		<?php if($transaction['status']==='pending'): ?>
			<span class="label label-danger">Pending</span>
			<a class="btn btn-xs btn-success" href="<?php echo base_url('invoice/'.$transaction['hash']);?>">Pay Now</a>
		<?php elseif($transaction['status']==='waiting'): ?>
			<span class="label label-warning">Waiting Confirmations</span>
		<?php else: ?>
			<span class="label label-success"><?php echo ucfirst($transaction['status']); ?></span>
		<?php endif; ?>
	</td>
	<?php
		}} else {
	?>
	<tr>
		<td colspan="3" class="text-center">No Records Found !!</td>
	</tr>
<?php
	}
?>

<!-- Referall Link -->
<?php echo base_url('r/'.$this->userdata->unique_id); ?>

<!-- Purchase Section Start-->
<!-- Purchase Price -->
<?php echo $invoice['amount']; ?> <?php echo settings('currency_code'); ?>

<!-- Payment Address -->
<?php echo $params['address']; ?>

<!-- Send Exactly -->
<?php echo $params['amount']; ?> <?php echo settings('currency_name'); ?>

<!-- Confirmations -->
<?php echo $params['confirms_needed'];?>

<!-- Timeout -->
<?php echo $time_left->i.'m '.$time_left->s.'s';?>

<!-- QRCODE -->
<?php echo $params['qrcode_url']; ?>

<!-- Tracking Payments -->
<a href="<?php echo $params['status_url']; ?>">Payment Status</a>
<!-- Purchase Section End-->
