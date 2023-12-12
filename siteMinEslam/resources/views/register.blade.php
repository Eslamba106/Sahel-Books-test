<?php $settings = get_settings(); ?>
<section class="section p-0 mt--50">
  <div class="container">

    @if ($settings->enable_registration == 0)
    <div class="col-md-12 text-center">
      <h2 class="text-danger p-200">{{ helper_trans('registration-system-is-disabled') }}!</h2>
    </div>
    @else
    <div class="row">
      <div class="col-md-12 text-center">
        <a class="site-logo" href="{{ url('') }}">
          <img class="img-fluid" width="70%" src="{{ $settings->logo }}" alt="demo" />
        </a>
        <h3 class="mb-0">{{ html_escape($settings->site_title) }}</h3>
        <h4 class="mt-2">{{ html_escape($settings->site_name) }}<span> {{ helper_trans('register-info') }}</span></h4>
      </div>
    </div>

    <div class="spacer py-2"></div>

    <div class="row">
      <ul class="progressbar">
        <li class="step_1 active">{{ helper_trans('sign-up') }}</li>
        <li class="step_2">{{ helper_trans('business') }}</li>
      </ul>
    </div>

    <div class="account_area row justify-content-md-center mt-0">

      <div class="col-md-6 col-lg-6 col-sm-12 text-left d-none d-md-block" data-aos="fade-right">
        <img class="mt-5" width="95%" src="assets/front/img/register.jpg">
      </div>

      <div class="col-md-6 col-lg-6 col-sm-12 text-left" data-aos="fade-left">

        <div class="spacer py-7"></div>

        <form id="register_form" class="authorization__form authorization__form--shadow leave_con" method="post" action="{{ url('register_user'); }}">
          @csrf
          <h4 class="__title">{{ helper_trans('sign-up') }}</h4>
          <div class="input-wrp">
            <input class="textfield textfield--grey" type="text" name="name" placeholder="{{ helper_trans('full-name') }}" required />
          </div>

          <div class="input-wrp">
            <input class="textfield textfield--grey" type="email" name="email" placeholder="{{ helper_trans('email') }}" required />
          </div>

          <div class="input-wrp">
            <input class="textfield textfield--grey" type="password" name="password" placeholder="{{ helper_trans('password') }}" required />
          </div>

          <p>
            <label class="checkbox mt-0">
              <input name="agree" class="agree_btn" type="checkbox" value="ok" required />
              <i class="fontello-check"></i><span>{{ helper_trans('agree-with') }} <a target="_blank" href="{{ url('terms-of-service') }}">{{ helper_trans('terms-of-service') }}</a></span>
            </label>
          </p>

          <input type="hidden" name="plan" value="<?php if (isset($_GET['plan'])) {
                                                    echo html_escape($_GET['plan']);
                                                  } else {
                                                    echo "basic";
                                                  } endif ?>">
          <input type="hidden" name="billing" value="<?php if (isset($_GET['billing'])) {
                                                        echo html_escape($_GET['billing']);
                                                      } else {
                                                        echo "monthly";
                                                      } ?>">

          <div class="input-wrp">
            <?php if ($settings->enable_captcha == 1 && $settings->captcha_site_key != '') : ?>
              <div class="g-recaptcha pull-left" data-sitekey="{{ html_escape($settings->captcha_site_key); }}"></div>
            <?php endif ?>
          </div>

          <div class="text-center">
            <button class="custom-btn custom-btn--medium custom-btn--style-2 wide submit_btn mb-4 loader_btn" type="submit" disabled="disabled">{{ helper_trans('get-started') }} </button>
            <a class="create" href="{{ url('login'); }}">{{ helper_trans('sign-in') }}</a>
          </div>

        </form>
      </div>
    </div>




    <div class="business_area justify-content-md-center mt-0" style="display: none;">
      <div class="row">
        <div class="col-md-6 col-lg-6 col-sm-12 text-left d-none d-md-block" data-aos="fade-up">
          <img src="assets/front/img/business.jpg">
        </div>

        <div class="col-md-6 col-lg-6 col-sm-12 text-left" data-aos="fade-down">

          <div class="spacer py-7"></div>

          <form id="business_form" class="authorization__form authorization__form--shadow leave_con" method="post" action="{{ url('create-business'); }}">
            @csrf
            <h4 class="__title">{{ helper_trans('setup-your-first-business') }}</h4>
            <div class="input-wrp">
              <input class="textfield textfield--grey" type="text" name="name" placeholder="Business Name" />
            </div>

            <div class="input-wrp">
              <select class="selectfield textfield--grey single_select col-sm-12 wd-100" id="country" name="country" style="width: 100%">
                <option value="">{{ helper_trans('select-country') }}</option>
                <?php foreach ($countries as $country) : ?>
                  <option value="{{ html_escape($country->id); }}">
                    {{ html_escape($country->name); }}
                  </option>
                <?php endforeach ?>
              </select>
            </div>

            <div class="input-wrp">
              <select class="selectfield textfield--grey single_select col-sm-12 wd-100" name="category" style="width: 100%">
                <option value="">{{ helper_trans('select-business-type') }}</option>
                <?php foreach ($business as $busines) : ?>
                  <option value="{{ html_escape($busines->id); }}">
                    {{ html_escape($busines->name); }}
                  </option>
                <?php endforeach ?>
              </select>
            </div>

            <button class="custom-btn custom-btn--medium custom-btn--style-2 wide" type="submit" role="button">{{ helper_trans('create') }}</button>
          </form>
        </div>
      </div>
    </div>



    <div class="loader"></div>

    <div class="pricing_area row justify-content-md-center mt-0 text-center" style="display: none;">

      <div class="spacer py-4"></div>

      <h4 class="__title">{{ helper_trans('you-are-almost-done') }}!</h4>
      <div class="col-md-12 col-lg-12 col-sm-12 text-left scroll-x" data-aos="fade-down">
        <div class="pricing-table pricing-table--s4" data-aos="fade" data-aos-delay="150">
          <div class="d-block">
            <div class="col-md-4 text-center m-auto mb-20">
              <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-outline-primary custom-btngp">
                  <input type="radio" name="payment_type" value="monthly" class="switch_price" id="monthly-1"> {{ helper_trans('monthly') }}
                </label>
                <label class="btn btn-outline-primary custom-btngp">
                  <input type="radio" name="payment_type" value="yearly" class="switch_price" id="yearly-1" checked> {{ helper_trans('yearly') }}
                </label>
              </div>
            </div><br><br>

            <table class="text-center rounded">
              <tbody>
                <thead class="thead mb-100">

                  <tr class="">
                    <th>
                      <div class="h2"></div>
                    </th>
                    <?php $i = 1;
                    foreach ($packages as $package) : ?>
                      <th class="pt-40 text-center <?php if ($i == 2) {
                                                      echo "colm_2";
                                                    } ?>">
                        <div class="__header">
                          <div class="__title h3">{{ html_escape($package->name); }} </div>
                        </div>
                        <?php if (settings()->enable_discount == 1) : ?>
                          <h4>
                            <?php if ($package->dis_month != 0 && $package->price != 0) : ?>
                              <span class="monthly_show soft-blue" style="display: none;">
                                {{ html_escape($package->dis_month); }}% {{ helper_trans('off') }}
                              </span>
                            <?php endif ?>

                            <?php if ($package->dis_year != 0 && $package->price != 0) : ?>
                              <span class="yearly_show soft-blue">
                                {{ html_escape($package->dis_year); }}% {{ helper_trans('off') }}
                              </span>
                            <?php endif ?>
                          </h4>
                        <?php endif ?>
                      </th>
                    <?php $i++;
                    endforeach; ?>
                  </tr>

                  <tr>
                    <td>
                      <h2></h2>
                    </td>
                    <?php $a = 1;
                    foreach ($packages as $package) : ?>
                      <td class="p-0">
                        <div class="theader <?php if ($a == 2) {
                                              echo "colm_2";
                                            } endif ?>">
                          <div class="__price mb-5 <?php if ($a == 2) {
                                                      echo "colm_2";
                                                    } endif ?>">

                            <span class="price_year <?php if (settings()->enable_discount == 1 && $package->dis_year != 0 && $package->price != 0) {
                                                      echo "price-off";
                                                    };?>">
                              {{ currency_to_symbol(settings()->currency); }}{{ round($package->price); }}
                            </span>

                            <?php if (settings()->enable_discount == 1 && $package->dis_month != 0 && $package->price != 0) : ?>
                              <span class="price_year">
                                {{ currency_to_symbol(settings()->currency); }}{{ get_discount($package->price, $package->dis_year) }}
                              </span>
                            <?php endif ?>

                            <span class="price_month <?php if (settings()->enable_discount == 1 && $package->dis_month != 0 && $package->price != 0) {
                                                        echo "price-off";
                                                      } endif ?>" style="display: none;">
                              {{ currency_to_symbol(settings()->currency); }}{{ round($package->monthly_price); }}
                            </span>

                            <?php if (settings()->enable_discount == 1 && $package->dis_month != 0 && $package->price != 0) : ?>
                              <span class="price_month" style="display: none;">
                                {{ currency_to_symbol(settings()->currency); }}{{ get_discount($package->monthly_price, $package->dis_month) }}
                              </span>
                            <?php endif ?>

                          </div>
                          <p class="mt-0 bill_type">{{ helper_trans('per-year') }}</p>
                        </div>
                      </td>
                    <?php $a++;
                    endforeach; ?>
                  </tr>
                </thead>

                <?php foreach ($features as $feature) : ?>
                  <tr class="monthly_row" style="display: none">
                    <td class="text-right">{{ html_escape($feature->name); }}</td>
                    <td class="text-center">
                      <?php if ($feature->basic == 0) : ?>
                        <p class="mb-0 feature-item"><i class="ico-unchecked fontello-minus"></i></p>
                      <?php elseif ($feature->basic == 1) : ?>
                        <p class="mb-0 feature-item"><i class="ico-checked fontello-ok"></i></p>
                      <?php elseif ($feature->basic == 2) : ?>
                        <p class="mb-0 feature-item">{{ helper_trans('unlimited') }}</p>
                      <?php else : ?>
                        {{ html_escape($feature->basic); }}
                      <?php endif ?>
                    </td>
                    <td class="text-center colm_2">
                      <?php if ($feature->standared == 0) : ?>
                        <p class="mb-0 feature-item"><i class="ico-unchecked fontello-cancel"></i></p>
                      <?php elseif ($feature->standared == 1) : ?>
                        <p class="mb-0 feature-item"><i class="ico-checked fontello-ok"></i></p>
                      <?php elseif ($feature->standared == 2) : ?>
                        <p class="mb-0 feature-item">{{ helper_trans('unlimited') }}</p>
                      <?php else : ?>
                        {{ html_escape($feature->standared); }}
                      <?php endif ?>
                    </td>
                    <td class="text-center">
                      <?php if ($feature->premium == 0) : ?>
                        <p class="mb-0 feature-item"><i class="ico-unchecked fontello-minus"></i></p>
                      <?php elseif ($feature->premium == 1) : ?>
                        <p class="mb-0 feature-item"><i class="ico-checked fontello-ok"></i></p>
                      <?php elseif ($feature->premium == 2) : ?>
                        <p class="mb-0 feature-item">{{ helper_trans('unlimited') }}</p>
                      <?php else : ?>
                        {{ html_escape($feature->premium); }}
                      <?php endif ?>
                    </td>
                  </tr>
                <?php endforeach; ?>

                <?php foreach ($features as $feature) : ?>
                  <tr class="yearly_row">
                    <td class="text-right">{{ html_escape($feature->name); }}</td>
                    <td class="text-center">
                      <?php if ($feature->year_basic == 0) : ?>
                        <p class="mb-0 feature-item"><i class="ico-unchecked fontello-minus"></i></p>
                      <?php elseif ($feature->year_basic == 1) : ?>
                        <p class="mb-0 feature-item"><i class="ico-checked fontello-ok"></i></p>
                      <?php elseif ($feature->year_basic == 2) : ?>
                        <p class="mb-0 feature-item">{{ helper_trans('unlimited') }}</p>
                      <?php else : ?>
                        {{ html_escape($feature->year_basic); }}
                      <?php endif ?>
                    </td>
                    <td class="text-center colm_2">
                      <?php if ($feature->year_standared == 0) : ?>
                        <p class="mb-0 feature-item"><i class="ico-unchecked fontello-cancel"></i></p>
                      <?php elseif ($feature->year_standared == 1) : ?>
                        <p class="mb-0 feature-item"><i class="ico-checked fontello-ok"></i></p>
                      <?php elseif ($feature->year_standared == 2) : ?>
                        <p class="mb-0 feature-item">{{ helper_trans('unlimited') }}</p>
                      <?php else : ?>
                        {{ html_escape($feature->year_standared); }}
                      <?php endif ?>
                    </td>
                    <td class="text-center">
                      <?php if ($feature->year_premium == 0) : ?>
                        <p class="mb-0 feature-item"><i class="ico-unchecked fontello-minus"></i></p>
                      <?php elseif ($feature->year_premium == 1) : ?>
                        <p class="mb-0 feature-item"><i class="ico-checked fontello-ok"></i></p>
                      <?php elseif ($feature->year_premium == 2) : ?>
                        <p class="mb-0 feature-item">{{ helper_trans('unlimited') }}</p>
                      <?php else : ?>
                        {{ html_escape($feature->year_premium); }}
                      <?php endif ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
                <tfoot>
                  <tr class="btom">
                    <td></td>
                    <?php $b = 1;
                    foreach ($packages as $package) : ?>
                      <td class="<?php if ($b == 2) {
                                    echo "colm_2";
                                  } ?>">
                        <a class="custom-btn custom-btn--medium custom-btn--style-3 package_btn" href="{{ url('package/' . $package->id) }}">{{ helper_trans('choose-plan') }}</a>
                        <input type="hidden" name="billing_type" class="billing_type" value="yearly">
                      </td>
                    <?php $b++;
                    endforeach; ?>
                  </tr>
                </tfoot>

              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
  @endif  

  </div>
</section>