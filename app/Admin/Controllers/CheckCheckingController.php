<?php

namespace App\Admin\Controllers;

use App\Check;
use App\Image;
use App\Partner;
use App\PartnerType;
use Encore\Admin\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CheckCheckingController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Check';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Check);
        $grid->model()->where('status', '=', Check::STATUS_CHECKING);

        $grid->column('id', __('Id'));
        $grid->column('user.email', __('User'));
        $grid->column('status_string', __('Status'));
        $grid->column('images')->display(function ($pictures) {
            return $pictures[0]['path']??null;
        })->image('', 400, 400)->width(400);
        $grid->column('created_at', __('Created at'));

        $grid->filter(function ($filter) {
            /** @var $filter Grid\Filter */
            $filter->equal('partner_id', __('Partner'))->select(Partner::partnerList());
            $filter->equal('status', __('Status'))->select(Check::statusList());
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
        $show = new Show(Check::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('partner_id', __('Partner id'));
        $show->field('status', __('Status'));
        $show->field('status_description', __('Status description'));
        $show->field('sum', __('Sum'));
        $show->field('offer_id', __('Offer id'));
        $show->field('cashback_sum', __('Cashback sum'));
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
        $form = new Form(new Check);

        $modelOptions = [];
        if ($id = request()->route()->parameter('check')) {
            $model = Check::query()
                ->with('partner.offers')
                ->where('id', $id)
                ->first();

            $modelOffers = $model->partner->offers ?? null;

            if (!empty($modelOffers)) {
                foreach ($modelOffers as $modelOffer) {
                    $modelOptions[$modelOffer->id] = "{$modelOffer->typeString}: {$modelOffer->description}";
                }
            }
        }

        $form->hidden('user_id')->value(1);
        $form->select('partner_id', __('Partner id'))
            ->options(Partner::partnerList());
        $form->select('offer_id', __('Offer id'))->options($modelOptions);
        $form->select('status', __('Status'))
            ->options(Check::statusList())
            ->required();
        $form->textarea('status_description', __('Status description'));
        $form->decimal('sum', __('Sum'));
        $form->decimal('cashback_sum', __('Cashback sum'));

        $form->hasMany('images',
            __('Images'),
            function (Form\NestedForm $form) {
                $form->image('path');
                $form->hidden('object_type')->value(Image::TYPE_CHECK);
            }
        );

        $script = <<<SCRIPT
        $(document).on('select2:select', 'select.partner_id', function (event) {
            $("select.offer_id").empty();
            var partnerId = ($(this).val());
            $.ajax({
              type: 'GET',
              url: '/ajax/partner-offers/' + partnerId,
              success: function(response){
                $.each(response, function (index, item) {
                    $('<option/>',{value:item.id,text:item.type_string + ':' + item.description}).appendTo("select.offer_id");
                })
              },
            });
        });
SCRIPT;

        Admin::script($script);

        return $form;
    }
}
