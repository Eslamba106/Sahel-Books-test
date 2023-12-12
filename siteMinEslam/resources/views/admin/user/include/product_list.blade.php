{{-- {{$taxes = \App\Models\Invoice_Items_Taxes::all()}} --}}

    <tr class="item-row" id="taxes_row">
        <td>
            <input type="text" class="form-control item" placeholder="Item" type="text" name="items_val[]" value="<?php echo html_escape($product->name) ?>">  
             <input type="hidden" class="form-control item" placeholder="Item" type="text" name="items[]" value="<?php echo html_escape($product->id) ?>">
        </td>
        <td>
            <input class="form-control" placeholder="Enter item description" type="text" name="details[]" value="<?php echo html_escape($product->details) ?>"> 
        </td>
        <td>
            <input class="form-control price invo" placeholder="Price" id="price" type="text" name="price[]" value="<?php echo html_escape($product->price) ?>"> 
        </td>
        <td>
            <input class="form-control qty" id="Quantity" placeholder="Quantity" type="text" name="quantity[]" value="1">
        </td>
        <td id="td_taxes">
            <select name="taxes[]" id="tax" class="form-control taxes_product">
                <option value="0"><?php echo helper_trans('No Taxes'); ?></option>
                @foreach ($taxes as $item)
                    <option value="{{$item->rate}}">{{ $item->name }} {{$item->rate * 100}}%</option>
                    {{-- <input type="hidden" name="taxes_id" value="{{$item->id}}"> --}}
                @endforeach
            </select>
        </td>
        <td id="td_discount">
            <?php 
                $discounts = [0 ,0.1 , 0.2 , 0.3 , 0.4 , 0.5 , 0.6 , 0.7 , 0.8 , 0.9 , 1];     
            ?>
            <select name="Discount[]" id="Discount" class="form-control Discount">
                @foreach ($discounts as $item)
                    <option value="{{$item}}">{{ $item * 100 }}%</option>
                @endforeach
            </select>
        </td>
        <td width="15%">
            <div class="delete-btn">
                <span class="currency_wrapper"></span>
                <span class="total"><?php echo html_escape($product->price) ?></span>
                <a class="delete" href="javascript:;" title="Remove row">&times;</a>
                <input type="hidden" class="total" name="total_price[]" value="<?php echo html_escape($product->price) ?>">
                <input type="hidden" name="product_ids[]" value="<?php echo html_escape($product->id) ?>">
            </div>
        </td>
    </tr>



