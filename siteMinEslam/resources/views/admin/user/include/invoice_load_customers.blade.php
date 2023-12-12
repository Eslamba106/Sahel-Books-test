<select class="form-control single_select" name="customer" id="customer">
    <option value=""><?php echo helper_trans('select-customer'); ?></option>
    <?php foreach ($customers as $customer) : ?>
    <option value="{{ $customer->id }}" <?php echo !empty($invoice) && $invoice[0]['customer'] == $customer->id ? 'selected' : ''; ?>><?php echo html_escape($customer->name); ?></option>
    <?php endforeach ?>
</select>
