<?php

namespace App\Admin\Controllers;

use App\Image;
use App\Offer;
use App\Partner;
use App\PartnerOption;
use App\PartnerType;
use Encore\Admin\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\App;

class PartnerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Partner';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Partner);

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('partnerType.title', __('Partner type'));
        $grid->column('phone', __('Phone'));
        $grid->column('mail', __('Mail'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        $grid->filter(function ($filter) {
            /** @var $filter Grid\Filter */
            $filter->like('name', __('Name'));
            $filter->equal('partner_type_id', __('Partner type'))->select(PartnerType::query()->pluck('title', 'id'));
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Partner::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->partnerType(__('Partner type'), function ($partnerType) {

            /** @var $partnerType Show */
            $partnerType->setResource('/admin/partner-types');
            $partnerType->title();
        });
        $show->field('partnerType.title', __('Partner type'));
        $show->field('address', __('Address'));
        $show->field('phone', __('Phone'));
        $show->field('mail', __('Mail'));
        $show->field('description', __('Description'));
        $show->field('cashback_description', __('Cashback description'));
        $show->field('logo', __('Logo'))->image();
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Partner);

        $partnerOptions = PartnerOption::query()
            ->get(['id', 'type', 'name']);

        $partnerOptionsSet = [];
        foreach ($partnerOptions as $partnerOption) {
            $value = (PartnerOption::LABELS[$partnerOption->type]??$partnerOption->type) .
                ":" . $partnerOption->name;
            $partnerOptionsSet[$partnerOption->id] = $value;
        }

        $form->text('name', __('Name'));
        $form->select('partner_type_id', __('Partner type id'))->options(
            PartnerType::query()->pluck('title', 'id')
        );
        $form->multipleSelect('partner_options', __('Partner options'))
            ->options($partnerOptionsSet);
        $form->text('address', __('Address'));
        $form->textarea('schedule', __('Schedule'));
        $form->mobile('phone', __('Phone'))->options(['mask' => '(999)9999999']);
        $form->email('mail', __('Mail'));
        $form->textarea('description', __('Description'));
        $form->textarea('cashback_description', __('Cashback description'));
        $form->image('logo', __('Logo'));

        $form->hasMany('images',
            __('Images'),
            function (Form\NestedForm $form) {
                $form->image('path');
                $form->hidden('object_type')->value(Image::TYPE_PARTNER);
            }
        );

        $form->hasMany('offers', __('Offers'),
            function (Form\NestedForm $form) {
                $form->select('type', __('Type'))
                    ->options([
                        Offer::BASE_OFFER_TYPE => 'Обычный кэшбэк',
                        Offer::SPECIAL_OFFER_TYPE => 'Акционный кэшбэк',
                        Offer::DATE_BASE_OFFER_TYPE => 'Кэшбэк на выбранный период',
                    ])->addElementClass('offer-select');

                $form->datetime('start_date', __('Start date'));
                $form->datetime('finish_date', __('Finish date'));
                $form->text('description', __('Description'));
                $form->decimal('value', __('Value'));

            }
        );

        $script = <<<SCRIPT
        $(document).on('select2:select', 'select.type', function (event) {
            var selectDiv = $(this).closest('.fields-group');
            var value = $(this).val();
            var startDateDiv = selectDiv.find('input#start_date').closest('.form-group');
            var finishDateDiv = selectDiv.find('input#finish_date').closest('.form-group');
            
            
            if (value == 3) {
                startDateDiv.show();
                finishDateDiv.show();

            } else {
                startDateDiv.hide();
                finishDateDiv.hide();
            }
        });
SCRIPT;

        Admin::script($script);

        return $form;
    }
}
