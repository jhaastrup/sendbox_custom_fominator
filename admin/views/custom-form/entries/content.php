<?php
/**
 * JS reference : assets/js/admin/layout.js
 */

/** @var $this Forminator_CForm_View_Page */
$count             = $this->filtered_total_entries();
$is_filter_enabled = $this->is_filter_box_enabled();

if ( $this->error_message() ) : ?>

	<span class="sui-notice sui-notice-error"><p><?php echo esc_html( $this->error_message() ); ?></p></span>

<?php endif;

if ( $this->total_entries() > 0 ) : ?>

	<form method="GET" class="forminator-entries-actions">

		<input type="hidden" name="page" value="<?php echo esc_attr( $this->get_admin_page() ); ?>">
		<input type="hidden" name="form_type" value="<?php echo esc_attr( $this->get_form_type() ); ?>">
		<input type="hidden" name="form_id" value="<?php echo esc_attr( $this->get_form_id() ); ?>">

		<div class="fui-pagination-entries sui-pagination-wrap">

			<span class="sui-pagination-results"><?php if ( 1 === $count ) { printf( esc_html__( '%s result', Forminator::DOMAIN ), $count ); } else { printf( esc_html__( '%s results', Forminator::DOMAIN ), $count ); } // phpcs:ignore ?></span>

			<?php $this->paginate(); ?>

		</div>

		<div class="sui-box fui-box-entries">

			<fieldset class="forminator-entries-nonce">
				<?php wp_nonce_field( 'forminatorCustomFormEntries', 'forminatorEntryNonce' ); ?>
			</fieldset>

			<div class="sui-box-body fui-box-actions">

				<?php $this->template( 'custom-form/entries/prompt' ); ?>

				<div class="sui-box-search">

					<div class="sui-search-left">

						<?php $this->bulk_actions(); ?>

					</div>

					<div class="sui-search-right">

						<div class="sui-pagination-wrap">

							<span class="sui-pagination-results"><?php if ( 1 === $count ) { printf( esc_html__( '%s result', Forminator::DOMAIN ), $count ); } else { printf( esc_html__( '%s results', Forminator::DOMAIN ), $count ); } // phpcs:ignore ?></span>

							<?php $this->paginate(); ?>

							<button class="sui-button-icon sui-button-outlined forminator-toggle-entries-filter <?php echo( $is_filter_enabled ? 'sui-active' : '' ); ?>">
								<i class="sui-icon-filter" aria-hidden="true"></i>
							</button>

						</div>

					</div>

				</div>

				<?php $this->template( 'custom-form/entries/filter' ); ?>

			</div>

			<?php if ( true === $is_filter_enabled ) : ?>

				<div class="sui-box-body fui-box-actions-filters">

					<label class="sui-label"><?php esc_html_e( 'Active Filters', Forminator::DOMAIN ); ?></label>

					<div class="sui-pagination-active-filters forminator-entries-fields-filters">

						<?php if ( isset( $this->filters['search'] ) ) : ?>
							<div class="sui-active-filter">
								<?php printf(
									esc_html__( 'Keyword: %s', Forminator::DOMAIN ),
									esc_html( $this->filters['search'] )
								); ?>
								<button class="sui-active-filter-remove" type="submit" name="search" value="">
									<span class="sui-screen-reader-text"><?php esc_html_e( 'Remove this keyword', Forminator::DOMAIN ); ?></span>
								</button>
							</div>
						<?php endif; ?>

						<?php if ( isset( $this->filters['min_id'] ) ) : ?>
							<div class="sui-active-filter">
								<?php printf(
									esc_html__( 'From ID: %s', Forminator::DOMAIN ),
									esc_html( $this->filters['min_id'] )
								); ?>
								<button class="sui-active-filter-remove" type="submit" name="min_id" value="">
									<span class="sui-screen-reader-text"><?php esc_html_e( 'Remove this keyword', Forminator::DOMAIN ); ?></span>
								</button>
							</div>
						<?php endif; ?>

						<?php if ( isset( $this->filters['max_id'] ) ) : ?>
							<div class="sui-active-filter">
								<?php printf(
									esc_html__( 'To ID: %s', Forminator::DOMAIN ),
									esc_html( $this->filters['max_id'] )
								); ?>
                                <button class="sui-active-filter-remove" type="submit" name="max_id" value="">
									<span class="sui-screen-reader-text"><?php esc_html_e( 'Remove this keyword', Forminator::DOMAIN ); ?></span>
								</button>
							</div>
						<?php endif; ?>

						<?php if ( isset( $this->filters['date_created'][0] ) || isset( $this->filters['date_created'][1] ) ) : ?>
							<div class="sui-active-filter">
								<?php printf(
									esc_html__( 'Submission Date Range: %1$s to %2$s', Forminator::DOMAIN ),
									esc_html( $this->filters['date_created'][0] ),
									esc_html( $this->filters['date_created'][1] )
								); ?>
								<button class="sui-active-filter-remove" type="submit" name="date_range" value="">
									<span class="sui-screen-reader-text"><?php esc_html_e( 'Remove this keyword', Forminator::DOMAIN ); ?></span>
								</button>
							</div>
						<?php endif; ?>

						<div class="sui-active-filter">
							<?php
							esc_html_e( 'Sort Order', Forminator::DOMAIN );
							echo ': ';
							if ( 'DESC' === $this->order['order'] ) {
								esc_html_e( 'Descending', Forminator::DOMAIN );
							} else {
								esc_html_e( 'Ascending', Forminator::DOMAIN );
							} ?>
						</div>

					</div>

				</div>

			<?php endif; ?>

			<table class="sui-table sui-table-flushed sui-accordion fui-table-entries">

				<?php $this->entries_header(); ?>

				<tbody>

					<?php
					foreach ( $this->entries_iterator() as $entries ) {

						$entry_id    = $entries['id'];
						$db_entry_id = isset( $entries['entry_id'] ) ? $entries['entry_id'] : '';

						$summary       = $entries['summary'];
						$summary_items = $summary['items'];

						$detail       = $entries['detail'];
						$detail_items = $detail['items'];
						?>

						<tr class="sui-accordion-item" data-entry-id="<?php echo esc_attr( $db_entry_id ); ?>">

							<?php foreach ( $summary_items as $key => $summary_item ) { ?>

								<?php if ( ! $summary['num_fields_left'] && ( count( $summary_items ) - 1 ) === $key ) :

									echo '<td>';

										echo esc_html( $summary_item['value'] );

										echo '<span class="sui-accordion-open-indicator">';

											echo '<i class="sui-icon-chevron-down"></i>';

										echo '</span>';

									echo '</td>';

								elseif ( 1 === $summary_item['colspan'] ) :

									echo '<td class="sui-accordion-item-title">';

										echo '<label class="sui-checkbox">';

											echo '<input type="checkbox" name="entry[]" value="' . esc_attr( $db_entry_id ) . '" id="wpf-cform-module-' . esc_attr( $db_entry_id ) . '" />';

											echo '<span aria-hidden="true"></span>';

											echo '<span class="sui-screen-reader-text">' . sprintf( esc_html__( 'Select entry number %s', Forminator::DOMAIN ), esc_html( $db_entry_id ) ) . '</span>';

										echo '</label>';

										echo esc_html( $db_entry_id );

									echo '</td>';

								else :

									echo '<td>';

										echo esc_html( $summary_item['value'] );

										echo '<span class="sui-accordion-open-indicator fui-mobile-only" aria-hidden="true">';
											echo '<i class="sui-icon-chevron-down"></i>';
										echo '</span>';

									echo '</td>';

								endif; ?>

							<?php } ?>

							<?php if ( $summary['num_fields_left'] ) {

								echo '<td>';
									echo '' . sprintf( esc_html__( "+ %s other fields", Forminator::DOMAIN ), esc_html( $summary['num_fields_left'] ) ) . '';
									echo '<span class="sui-accordion-open-indicator">';
										echo '<i class="sui-icon-chevron-down"></i>';
									echo '</span>';
								echo '</td>';

							} ?>

						</tr>

						<tr class="sui-accordion-item-content">

							<td colspan="<?php echo esc_attr( $detail['colspan'] ); ?>">

								<div class="sui-box fui-entry-content">

									<div class="sui-box-body"> 
                                         
										<h2 class="fui-entry-title"><?php echo '#' . esc_attr( $db_entry_id );
										
										?></h2>

										<?php foreach ( $detail_items as $detail_item ) { ?>

											<?php $sub_entries = $detail_item['sub_entries']; ?>

											<div class="sui-box-settings-slim-row sui-sm">

												<?php if ( isset( $detail_item['type'] ) && ( 'stripe' === $detail_item['type'] || 'paypal' === $detail_item['type'] ) ) {

													if ( ! empty( $sub_entries ) ) { ?>

														<div class="sui-box-settings-col-2">

															<span class="sui-settings-label sui-dark sui-sm"><?php echo esc_html( $detail_item['label'] ); ?></span>

															<table class="sui-table fui-table-details">

																<thead>

																	<tr>

																		<?php
																		$end = count( $sub_entries );
																		foreach ( $sub_entries as $sub_key => $sub_entry ) {

																			$sub_key++;

																			if ( $sub_key === $end ) {

																				echo '<th colspan="2">' . esc_html( $sub_entry['label'] ) . '</th>';

																			} else {

																				echo '<th>' . esc_html( $sub_entry['label'] ) . '</th>';

																			}

																		} ?>

																	</tr>

																</thead>

																<tbody>

																	<tr>

																		<?php
																		$end = count( $sub_entries );
																		foreach ( $sub_entries as $sub_key => $sub_entry ) {

																			$sub_key++;

																			if ( $sub_key === $end ) {

																				echo '<td colspan="2" style="padding-top: 5px; padding-bottom: 5px;">' . ( $sub_entry['value'] ) . '</td>'; // wpcs xss ok. html output intended

																			} else {

																				echo '<td style="padding-top: 5px; padding-bottom: 5px;">' . esc_html( $sub_entry['value'] ) . '</td>';

																			}

																		} ?>

																	</tr>

																</tbody>

															</table>

														</div>

													<?php }

												} else { ?>

													<div class="sui-box-settings-col-1">
														<span class="sui-settings-label sui-sm"><?php echo esc_html( $detail_item['label'] ); ?></span>
													</div>

													<div class="sui-box-settings-col-2">

														<?php if ( empty( $sub_entries ) ) { ?>

															<span class="sui-description"><?php echo ( $detail_item['value'] ); // wpcs xss ok. html output intended ?></span>

														<?php } else { ?>

															<?php foreach ( $sub_entries as $sub_entry ) { ?>

																<div class="sui-form-field">
																	<span class="sui-settings-label"><?php echo esc_html( $sub_entry['label'] ); ?></span>
																	<span class="sui-description"><?php echo ( $sub_entry['value'] ); // wpcs xss ok. html output intended ?></span>
																</div> 
                                                        
									

															<?php } ?>

														<?php } ?> 


													</div>

												<?php } ?>

											</div>
										

										<?php } ?> 
										

											<!---Sendbox part--->
											<div class = "sui-box-body">  
 
<?php



$form_id = $_REQUEST['form_id'];

$entry = Forminator_API::get_entry( $form_id , $db_entry_id);
$api_key = get_option('auth_token');

$values = $entry->meta_data;

//orgin object
$add_obj = $values['address-1'];
$ad = $add_obj['value']; 

//destination object
$add_des_obj = $values['address-2'];
$add_des = $add_des_obj['value'];



$name_obj = $values['name-1'];
$origin_name = $name_obj['value']; 

$des_name = $values['name-2'];
$destination_name = $des_name['value'];

//$o_street = $values['textarea-9'];
$origin_street = $ad['street_address'];

//$o_city = $values['textarea-3'];
$origin_city = $ad['city'];

//$des_city = $values['textarea-6'];
$destination_city =  $add_des['city'];

//$country_obj = $values['textarea-1'];
$origin_country =  $ad['country'];
	
//$state_obj =$values['textarea-2'];
$origin_state = $ad['state'];
	
//$des_country = $values['textarea-4'];
$destination_country = $add_des['country'];
	
//$des_state = $values['textarea-5'];
$destination_state = $add_des['state'];

$o_email = $values['email-1'];
$origin_email = $o_email['value']; 

$des_email = $values['email-2'];
$destination_email = $des_email['value'];


//$des_street = $values['textarea-10'];
$destination_street = $add_des['street_address'];

$o_phone = $values['phone-1'];
$origin_phone = $o_phone['value'];

$des_phone = $values['phone-2'];
$destination_phone = $des_phone['value'];

$weight_obj = $values['number-1'];
$weight = $weight_obj['value']; 

$content =  $values['textarea-1'];
$con_name = $content['value']; 

$value =  $values['currency-1'];
$con_value = $value['value'];



$date = new DateTime();
$date->modify('+1 day');
$pickup_date = $date->format('c');
$item_list =[];

$item_data = array(
   "name" => $con_name,
   "quantity" => "1",
   "value" =>  $con_value,
   "weight"=> $weight,
  "item_type_code" =>"other",
   "package_size_code" => "medium"
); 
array_push($item_list, $item_data);

$shipment_params = array(
	'origin_name' => $origin_name,
   'origin_country' => $origin_country,
   'origin_state' => $origin_state,
   'origin_city' => $origin_city,
   'origin_phone'=> $origin_phone,
   'origin_email' =>$origin_email,
   'origin_street' => $origin_street,
   'destination_name'=> $destination_name,
   'destination_country'=>$destination_country,
   'destination_state' => $destination_state,
   'destination_city' => $destination_city,
   'destination_street' =>$destination_street,
   'destination_email' => $destination_email,
   'destination_phone'=>$destination_phone,
   'weight' => $weight,
   'items'=> $item_list,
   'payment_option_code' => 'prepaid',
   'channel_code' => "mobile_web",
   'pickup_date' => $pickup_date,
  'deliver_priority_code' => 'next_day',
  "api_key" => $api_key
 // 'selected_courier_id'=> $rates_id
);  

$shipment_payload = json_encode($shipment_params);
//print($shipment_payload);
setcookie("shipment_payload_" . $db_entry_id, $shipment_payload);



if(!function_exists('preparePayload')){
function preparePayload($form_id, $db_entry_id){
	$entry = Forminator_API::get_entry( $form_id , $db_entry_id);
	
	$values = $entry->meta_data;
	$k = $entry->entry_id;
 
	//orgin object
$add_obj = $values['address-1'];
$ad = $add_obj['value']; 

//destination object
$add_des_obj = $values['address-2'];
$add_des = $add_des_obj['value'];

	
	$name_obj = $values['name-1'];
	$origin_name = $name_obj['value'];
	
	//$country_obj = $values['textarea-1'];
	$origin_country =  $ad['country'];
	
//$state_obj =$values['textarea-2'];
$origin_state = $ad['state'];
	
	//$des_country = $values['textarea-4'];
	$destination_country = $add_des['country'];
	
//$des_state = $values['textarea-5'];
$destination_state = $add_des['state'];

	
$weight_obj = $values['number-1'];
$weight = $weight_obj['value']; 	
	$payload_data = new stdClass();
	$payload_data->origin_country = $origin_country;
	$payload_data->origin_state = $origin_state;
	$payload_data->destination_country = $destination_country;
	$payload_data->destination_state = $destination_state;
	//$payload_data->destination_city = $destination_city;
	$payload_data->weight = $weight;
	return $payload_data;
	
};};  

global $wpdb;
$table_name = $wpdb->prefix .'frmt_form_entry_meta';

if (isset($_POST['shipment_code'])) {
	$form_id = $_REQUEST['form_id'];
	 //var_dump($form_id);//. $_POST['entry_id'] . $_POST['courier_id']);
	$shipment_code = $_POST['shipment_code'];
	$entry_id = $_POST['entry_id'];
	$check = $wpdb->get_results("SELECT * FROM $table_name WHERE meta_value='" . $shipment_code . "' AND meta_key='shipment_code' AND entry_id='". $entry_id ."'");
	if (empty($check)) {
		$wpdb->query("INSERT INTO $table_name (meta_key,meta_value,entry_id) VALUES ('shipment_code', '" . $shipment_code . "', '" . $entry_id . "') ");
   }	
	
}; 


if(!function_exists("fetchQuotes")){
function fetchQuotes($form_id, $db_entry_id){
	$payload_data = preparePayload($form_id, $db_entry_id); 
	$sendbox_obj = new Sendbox_API();
	$url = $sendbox_obj->get_sendbox_api_url('delivery_quote'); 
	$api_key = get_option('auth_token');
	$type = "application/json";
	
	$request_headers = array(
		"Content-Type: " .$type,
		"Authorization: " .$api_key,
	);
	//print($api_key);
	$data = wp_json_encode($payload_data);
	$quotes_res = $sendbox_obj->post_on_api_by_curl($url,$data,$api_key);
	$quotes_obj = json_decode($quotes_res);
	//print($data);
	$rates =$quotes_obj->rates;
	
	$option_string =""; 
	foreach ($rates as $rates_id => $rates_values){
		$rates_names = $rates_values->name;
		$rates_fee   = $rates_values->fee;
		$rates_id   = $rates_values->courier_id;
		$option_string.='<input name="rate" type ="radio" data-db-entry-id='.$db_entry_id.' data-courier-price = '.$rates_fee.' value='.$rates_id.'> '.$rates_names.' <br/> </input>';
	} 
	return $option_string;	
};};

$form_id = $_REQUEST['form_id'];

$option_string = fetchQuotes($form_id, $db_entry_id);


//sometestpart

//var_dump($ad);

 ?>
<?php ?>
<div> 
<form name="shipment-form" method="POST" action="">
 <div id="rates">
 <p id="error_<?php echo($db_entry_id)?>"></p>
			  <h2>Select a courier </h2>

			  <label><?php echo( $option_string )?></label>
			</div> 

			<p id="fee_<?php echo($db_entry_id)?>">Fee: ₦ 0.00</p>

<?php 
	$values = $entry->meta_data;
	if(isset($values['shipment_code']) && $values['shipment_code']['value']){
	$shipment_obj = $values['shipment_code'];
	$tracking_code = $shipment_obj['value'];
	$success_story = "your tracking code for this shipment is " .' '.$tracking_code; echo($success_story);}
	else { ?>
	<div id = "show_code">
 <button
data-db-entry-id="<?php echo($db_entry_id)?>"
id="ship-btn"
name = "sendbox-button"
type = "button"
class ="sui-button"
><?php esc_html_e("Ship with Sendbox") ?>

</button>
</div>
	<?php }  ?>
</form>
</div>


				</div>
				
<script>

function docReady(fn) {
    // see if DOM is already available
    if (document.readyState === "complete" || document.readyState === "interactive") {
        // call on next available tick
        setTimeout(fn, 1);
    } else {
        document.addEventListener("DOMContentLoaded", fn);
    }
}    

function getCookie(name) {
    // Split cookie string and get all individual name=value pairs in an array
    var cookieArr = document.cookie.split(";");
    
    // Loop through the array elements
    for(var i = 0; i < cookieArr.length; i++) {
        var cookiePair = cookieArr[i].split("=");
        
        /* Removing whitespace at the beginning of the cookie name
        and compare it with the given string */
        if(name == cookiePair[0].trim()) {
            // Decode the cookie value and return

            return decodeURIComponent(cookiePair[1]);
        }
    }
    
    // Return null if not found
    return null;
}
docReady(function () {

//console.log("scoobssssssssss");
const rad = document.getElementsByName("rate");
rad.forEach((e) => {
	e.checked = false;
	e.addEventListener('change', (() => {
		const dbEntryID = e.dataset.dbEntryId;
		console.log(dbEntryID);
		const courierPrice = e.dataset.courierPrice;
		console.log(courierPrice);
		let feeTag = document.getElementById(`fee_${dbEntryID}`);
		feeTag.innerText = ((courierPrice !== null && courierPrice !== undefined) ? "Fee: ₦"+ courierPrice : feeTag.innerText = "Fee: N0.00");
	}))
})

const sendboxButtons = document.getElementsByName("sendbox-button");

sendboxButtons.forEach((e) => {
	e.addEventListener('click', (() => {

		if(e.disabled){
			return
		}

		e.disabled = true;
		const dbEntryID = e.dataset.dbEntryId;
		const errorTag = document.getElementById(`error_${dbEntryID}`)
		errorTag.innerText = "";

		const rates = document.getElementsByName('rate');
        let rate_id;
        rates.forEach((e) => {
            if ((e.dataset.dbEntryId===dbEntryID) && (e.checked)){
                rate_id = e.value;
            };

        })
        if (!rate_id){
			const errorTag = document.getElementById(`error_${dbEntryID}`)
			errorTag.innerText = "Please Select Rate to proceed"
			e.disabled = false;
            return
        }


		let shipmentPayload = getCookie(`shipment_payload_${dbEntryID}`);
		//console.log(`shipment_payload_${dbEntryID}`);
		//console.log(shipmentPayload);
        shipmentPayload = JSON.parse(shipmentPayload);
       // console.log(typeof(shipmentPayload));

        shipmentPayload["selected_courier_id"] = rate_id;
        //console.log(shipmentPayload);
        const { api_key, ...shipmentPayloadData } = shipmentPayload;
		for(const key in shipmentPayloadData){
			let value = shipmentPayloadData[key];
			if (typeof(value) === 'string' || value instanceof String){
				console.log(value, value.indexOf("+"))
				let newVal = "";

				for (i=0; i<value.length; i++){
					if(value[i] !== "+"){
						newVal+=value[i] 
					}
					if (value[i]  === "+"){
						newVal += " ";
					}
				}
			shipmentPayloadData[key] = newVal;

			}
					}
        console.log(shipmentPayloadData);
		//console.log(api_key);
         postData('https://cors-anywhere.herokuapp.com/https://live.sendbox.co/shipping/shipments', shipmentPayloadData, api_key)
        .then((data) => {
            if (!data){
				e.disabled = false
				return}

				const shipment_code = data.code;
				const formData  = new FormData();

				formData.append("entry_id", dbEntryID);
				//formData.append("courier_id", rate_id); 
				formData.append("shipment_code", shipment_code);
			console.log('maaaaaaaaaaaaaaaaaaaa', data)

			console.log(dbEntryID, rate_id)
		   var form_id = document.getElementsByName("form_id");
                form_id.forEach((field) => {
                if (field.tagName === "INPUT" && field.value){
				 console.log(field.value)
                form_form_id = field.value;
                return
                }
                 })

       postPData(`admin.php?page=forminator-entries&form_type=forminator_forms&form_id=${form_form_id}`, formData)
		
		}) 
		  
	}))
})
async function postData(url, data, api_key) {
  // Default options are marked with *
  const response = await fetch(url, {
    method: 'POST', // *GET, POST, PUT, DELETE, etc.
    mode: 'cors', // no-cors, *cors, same-origin
    cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
    credentials: 'same-origin', // include, *same-origin, omit
    headers: {
      'Content-Type': 'application/json',
      // 'Content-Type': 'application/x-www-form-urlencoded',
      'Authorization': api_key
    },
    redirect: 'follow', // manual, *follow, error
    referrerPolicy: 'no-referrer', // no-referrer, *client
    body: JSON.stringify(data) // body data type must match "Content-Type" header
  });
  console.log(response)
  console.log(response.status)
  /* var form_id = document.getElementsByName("form_id");
  form_id.forEach((field) => {
if (field.tagName === "INPUT" && field.value){
form_form_id = field.value;
return
}
})
console.log(form_form_id); */
 
  const datat =  await response.json()
  console.log(datat);
  //return {"code": "122345"}
  if (response.status && response.status == "201"){
	document.getElementById("ship-btn").style.display = "none"
                alert(`Your sendbox tracking code for this order is ${datat["code"]}`); 
                document.getElementById("show_code").innerHTML = `<b>Your sendbox tracking code for this order is ${datat["code"]} </b>`;
				
	return datat
};

  if ("transaction" in datat){
	  alert("Insuffucuent balance login to sendbox and topup your wallet");
	  //datat.code = 12341
	  return datat   
  }
  alert("Unexpected error occured");
  return
}

    
async function postPData(url, data) {
  // Default options are marked with *
  //console.log(data);
  const response = await fetch(url, {

    method: 'POST', // *GET, POST, PUT, DELETE, etc.
    body: data
  });
  //console.log(response)
  //console.log(response.status)
  const datat =  await response.text()
//console.log(datat);
 
}

}); 

</script>

</div> 

<!---End of sendbox part--->

									<div class="sui-box-footer">

										<button
											type="button"
											class="sui-button sui-button-ghost sui-button-red wpmudev-open-modal"
											data-modal="delete-module"
											data-modal-title="<?php esc_attr_e( 'Delete Submission', Forminator::DOMAIN ); ?>"
											data-modal-content="<?php esc_attr_e( 'Are you sure you wish to permanently delete this submission?', Forminator::DOMAIN ); ?>"
											data-form-id="<?php echo esc_attr( $db_entry_id ); ?>"
											data-nonce="<?php echo wp_create_nonce( 'forminatorCustomFormEntries' ); // WPCS: XSS ok. ?>"
										>
											<i class="sui-icon-trash" aria-hidden="true"></i> <?php esc_html_e( "Delete", Forminator::DOMAIN ); ?>
										</button>

									</div>

								</div>

							</td>

						</tr>

					<?php } ?>

				</tbody>

			</table>

			<div class="sui-box-body fui-box-actions">

				<div class="sui-box-search">

					<?php $this->bulk_actions( 'bottom' ); ?>

				</div>

			</div>

		</div>

	</form>

<?php else : ?>

	<div class="sui-box sui-message">

		<?php if ( forminator_is_show_branding() ): ?>
			<img src="<?php echo esc_url( forminator_plugin_url() . 'assets/img/forminator-submissions.png' ); ?>"
				 srcset="<?php echo esc_url( forminator_plugin_url() . 'assets/img/forminator-submissions.png' ); ?> 1x, <?php echo esc_url( forminator_plugin_url() . 'assets/img/forminator-submissions@2x.png' ); ?> 2x"
				 alt="<?php esc_html_e( 'Forminator', Forminator::DOMAIN ); ?>"
				 class="sui-image"
				 aria-hidden="true"/>
		<?php endif; ?>

		<div class="sui-message-content">

			<h2><?php echo forminator_get_form_name( $this->form_id, 'custom_form' ); // WPCS: XSS ok. ?></h2>

			<p><?php esc_html_e( "You haven’t received any submissions for this form yet. When you do, you’ll be able to view all the data here.", Forminator::DOMAIN ); ?></p>

		</div>

	</div>

	

	

<?php endif; ?>
