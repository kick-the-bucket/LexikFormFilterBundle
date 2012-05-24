<?php

namespace Lexik\Bundle\FormFilterBundle\Filter\Extension\Type;

use Symfony\Component\Form\AbstractType as FormFieldType;
use Symfony\Component\Form\FormBuilder;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;

/**
 * Base filter type.
 *
 * @author Cédric Girard <c.girard@lexik.fr>
 */
class FieldFilterType extends FormFieldType implements FilterTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        parent::buildForm($builder, $options);

        if ($options['apply_filter'] instanceof \Closure || is_callable($options['apply_filter'])) {
            $builder->setAttribute('apply_filter', $options['apply_filter']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        return array_merge(parent::getDefaultOptions(), array(
             'required'     => false,
             'apply_filter' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'filter_field';
    }

    /**
     * {@inheritdoc}
     */
    public function getTransformerId()
    {
        return 'lexik_form_filter.transformer.default';
    }

    /**
     * Default implementation of the applyFieldFilter() method.
     * We just add a 'and where' clause.
     */
    public function applyFilter(QueryBuilder $queryBuilder, Expr $e, $field, $values)
    {
        if (!empty($values['value'])) {
            $queryBuilder->andWhere($e->eq($field, $values['value']));
        }
    }
}
