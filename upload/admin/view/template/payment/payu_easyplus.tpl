<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-payu-easyplus" class="btn btn-primary"><i class="fa fa-check-circle"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i><?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-payu-easyplus" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-payment-title"><?php echo $entry_payment_title; ?></label>
            <div class="col-sm-10">
              <input type="text" name="payu_easyplus_payment_title" value="<?php echo $payu_easyplus_payment_title; ?>" placeholder="<?php echo $entry_payment_title; ?>" id="input-payment-title" class="form-control" />
              <?php if ($error_payment_title) { ?>
                <div class="text-danger"><?php echo $error_payment_title; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-safe-key"><?php echo $entry_safe_key; ?></label>
            <div class="col-sm-10">
              <input type="text" name="payu_easyplus_safe_key" value="<?php echo $payu_easyplus_safe_key; ?>" placeholder="<?php echo $entry_safe_key; ?>" id="input-safe-key" class="form-control" />
              <?php if ($error_safe_key) { ?>
                <div class="text-danger"><?php echo $error_safe_key; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-api-username"><?php echo $entry_api_username; ?></label>
            <div class="col-sm-10">
              <input type="text" name="payu_easyplus_api_username" value="<?php echo $payu_easyplus_api_username; ?>" placeholder="<?php echo $entry_api_username; ?>" id="input-api-username" class="form-control" />
              <?php if ($error_api_username) { ?>
                <div class="text-danger"><?php echo $error_api_username; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-api-password"><?php echo $entry_api_password; ?></label>
            <div class="col-sm-10">
              <input type="text" name="payu_easyplus_api_password" value="<?php echo $payu_easyplus_api_password; ?>" placeholder="<?php echo $entry_api_password; ?>" id="input-api-password" class="form-control" />
              <?php if ($error_api_password) { ?>
                <div class="text-danger"><?php echo $error_api_password; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-system-to-call"><?php echo $entry_transaction_mode; ?></label>
            <div class="col-sm-10">
              <select name="payu_easyplus_transaction_mode" id="input-transaction_mode" class="form-control">
                <?php if ($payu_easyplus_transaction_mode == 'staging') { ?>
                  <option value="staging" selected="selected"><?php echo $text_staging; ?></option>
                <?php } else { ?>
                  <option value="staging"><?php echo $text_staging; ?></option>
                <?php } ?>
                <?php if ($payu_easyplus_transaction_mode == 'production') { ?>
                  <option value="production" selected="selected"><?php echo $text_production; ?></option>
                <?php } else { ?>
                  <option value="production"><?php echo $text_production; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-transaction-type"><span data-toggle="tooltip" title="<?php echo $help_transaction; ?>"><?php echo $entry_transaction_type; ?></label>
            <div class="col-sm-10">
              <select name="payu_easyplus_transaction_type" id="input-transaction-type" class="form-control">
                <?php if ($payu_easyplus_transaction_type == 'PAYMENT') { ?>
                  <option value="PAYMENT" selected="selected"><?php echo $text_payment; ?></option>
                <?php } else { ?>
                  <option value="PAYMENT"><?php echo $text_payment; ?></option>
                <?php } ?>
                <?php if ($payu_easyplus_transaction_type == 'RESERVE') { ?>
                  <option value="RESERVE" selected="selected"><?php echo $text_reserve; ?></option>
                <?php } else { ?>
                  <option value="RESERVE"><?php echo $text_reserve; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-payment-methods"><?php echo $entry_payment_methods; ?></label>
            <div class="col-sm-10">
              <select name="payu_easyplus_payment_methods[]" id="input-payment-methods" class="form-control" multiple="multiple">
                <?php foreach ($payment_methods as $payment_method) { ?>
                  <?php if (in_array($payment_method['value'], $payu_easyplus_payment_methods)) { ?>
                    <option value="<?php echo $payment_method['value']; ?>" selected="selected"><?php echo $payment_method['name']; ?></option>
                  <?php } else { ?>
                    <option value="<?php echo $payment_method['value']; ?>"><?php echo $payment_method['name']; ?></option>
                  <?php } ?>
                <?php } ?>
              </select>
              <?php if ($error_payment_methods) { ?>
                <div class="text-danger"><?php echo $error_payment_methods; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="payu_easyplus_total" value="<?php echo $payu_easyplus_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
            <div class="col-sm-10">
              <select name="payu_easyplus_order_status_id" id="input-order-status" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $payu_easyplus_order_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-payment-currency"><?php echo $entry_payment_currency; ?></label>
            <div class="col-sm-10">
            	<select name="payu_easyplus_payment_currency" id="input-payment-currency" class="form-control">
                <?php foreach ($supported_currencies as $currency) { ?>
                  <?php if ($currency['value'] == $payu_easyplus_payment_currency) { ?>
                    <option value="<?php echo $currency['value'] ?>" selected="selected"><?php echo $currency['name']; ?></option>
                  <?php } else { ?>
                  	<option value="<?php echo $currency['value'] ?>"><?php echo $currency['name']; ?></option>
                  <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-debug"><span data-toggle="tooltip" title="<?php echo $help_debug; ?>"><?php echo $entry_debug; ?></span></label>
            <div class="col-sm-10">
              <select name="payu_easyplus_debug" id="input-debug" class="form-control">
                <?php if ($payu_easyplus_debug) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-extended_debug"><?php echo $entry_extended_debug; ?></label>
            <div class="col-sm-10">
              <select name="payu_easyplus_extended_debug" id="input-extended_debug" class="form-control">
                <?php if ($payu_easyplus_extended_debug) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="payu_easyplus_sort_order" value="<?php echo $payu_easyplus_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="payu_easyplus_status" id="input-status" class="form-control">
                <?php if ($payu_easyplus_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group required">
              <input type="hidden" name="payu_easyplus_return_url" value="<?php echo $payu_easyplus_return_url; ?>" />
              <input type="hidden" name="payu_easyplus_cancel_url" value="<?php echo $payu_easyplus_cancel_url; ?>" />
              <input type="hidden" name="payu_easyplus_ipn_url" value="<?php echo $payu_easyplus_ipn_url; ?>" />
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 