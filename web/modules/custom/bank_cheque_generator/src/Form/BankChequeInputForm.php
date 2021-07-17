<?php
namespace Drupal\bank_cheque_generator\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Bank Cheque Generator Form Class
 * @package Drupal\bank_cheque_generator\Form
 */
class BankChequeInputForm extends FormBase {

  /**
   * @return string
   */
  public function getFormId()
  {
    return 'bank_cheque_generator';
  }


  /**
   * @param array $form
   * @param FormStateInterface $form_state
   * @return array
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {


    $form['form_wrap'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => [
          'row',
        ]
      ]
    ];

    $form['form_wrap']['instruction'] = [
      '#type' => 'html_tag',
      '#tag' => 'p',
      '#value' => $this->t('This is a form that will help you convert your input to Cheque Format.')
    ];

    $form['form_wrap']['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Drawer\'s First Name'),
      '#description' => $this->t('Enter drawer\'s  first name.'),
      '#required' => true
    ];

    $form['form_wrap']['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Drawer\'s Last Name'),
      '#description' => $this->t('Enter drawer\'s last name.'),
      '#required' => true
    ];

    $form['form_wrap']['payee_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Payee Name'),
      '#description' => $this->t('Enter Payee\'s name.'),
      '#required' => true
    ];

    $form['form_wrap']['pay_sum'] = [
      '#type' => 'number',
      '#title' => 'Payment Sum',
      '#description' => $this->t('Enter the payment sum, sum must be a positive integer.'),
      '#min' => 0,
      '#step' => 1,
      '#required' => true
    ];

    $form['form_wrap']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Generate'),
    ];


    $form['result'] = $form_state->getValue('result');


    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    if(strlen($form_state->getValue('first_name')) < 1) {
      $form_state->setErrorByName('first_name', $this->t('Drawer\'s  first name is required.'));
    }
    if(strlen($form_state->getValue('last_name')) < 1) {
      $form_state->setErrorByName('last_name', $this->t('Drawer\'s  last name is required.'));
    }
    if(strlen($form_state->getValue('payee_name')) < 1) {
      $form_state->setErrorByName('payee_name', $this->t('Payee\'s name is required.'));
    }
    if(strlen($form_state->getValue('pay_sum')) < 1) {
      $form_state->setErrorByName('pay_sum', $this->t('Payment sum is required.'));
    } else if ($form_state->getValue('pay_sum') < 1) {
      $form_state->setErrorByName('pay_sum', $this->t('Payment sum must be greater than or equal 1.'));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $form_state->setValue('result', '');

    $form_state->setRebuild(TRUE);

    $result = [
      '#type' => 'container',
    ];

    $result[] = [
      '#type' => 'html_tag',
      '#tag' => 'hr'
    ];

    $result['header'] = [
      '#type' => 'html_tag',
      '#tag' => 'h4',
      '#attributes' => [
        'class' => [
          'result-header',
        ]
      ],
      '#value' => 'Result',
    ];

    $result['bank_cheque'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => [
          'bank-cheque',
        ]
      ]
    ];

    $result['bank_cheque']['date'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => [
          'date-field',
        ]
      ],
      '#value' => '<span class="cheque-print">Date</span> <span class="cheque-write">'.date('j F, Y').'</span>'
    ];

    $result['bank_cheque']['payeeBox'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => [
          'payee-box',
        ]
      ],
    ];

    $result['bank_cheque']['payee'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => [
          'payee-field',
        ]
      ],
      '#value' => '<span class="cheque-print"><strong>Pay</strong></span> <span class="cheque-write">' . $form_state->getValue('payee_name') .'</span>'
    ];

    $result['bank_cheque']['sumInWord'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => [
          'sum-in-word-field',
        ]
      ],
      '#value' => '<span class="cheque-print"><strong>The sum of</strong></span> <span class="cheque-write">' . $this->convertSumToText($form_state->getValue('pay_sum')) .' '.  ($form_state->getValue('pay_sum') != 1 ? 'Dollars' : 'Dollar') .' Only &mdash;</span>'
    ];

    $result['bank_cheque']['sumInWordBox1'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => [
          'sum-in-word-box-1',
        ]
      ],
    ];

    $result['bank_cheque']['sumInWordBox2'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => [
          'sum-in-word-box-2',
        ]
      ],
    ];

    $result['bank_cheque']['sumInNumber'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => [
          'sumNumberBox',
        ]
      ],
      '#value' => '<span class="cheque-print dollar-sign">$</span> <span class="cheque-write pay-sum">' . number_format($form_state->getValue('pay_sum'), 0) . ' &mdash;</span>'
    ];

    $result['bank_cheque']['drawerName'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => [
          'drawer-field',
        ]
      ],
      '#value' => '<span class="cheque-print"><strong>' . sprintf('%s %s', $form_state->getValue('first_name'), $form_state->getValue('last_name')) .'</strong></span>'
    ];

    $form_state->setValue('result', $result);

    $form_state->setRebuild(TRUE);
  }

  private function convertSumToText(int $totalSum)
  {
    $list1 = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten',
      'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
    $list2 = ['', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred'];
    $list3 = ['', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
      'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
      'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
    ];

    $sumLength = strlen($totalSum); // Total number of digits of the total sum
    $levelBlocks = (int) (($sumLength + 2) / 3); // Total level blocks per 3-digit group
    $maximumDigits = $levelBlocks * 3; // Maximum number of digits of the total sum based on level blocks

    // Prepend leading zeros to allow first level block contains 3 digit
    $totalSum = substr('00' . $totalSum, -$maximumDigits);

    $totalSumLevelBlocks = str_split($totalSum, 3);

    $sumWordsArray = [];

    for($i = 0; $i < count($totalSumLevelBlocks); $i++) {
      $levelBlocks--;
      $hundreds = (int) ($totalSumLevelBlocks[$i] / 100);
      $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ( $hundreds == 1 ? '' : '' ) . ' ' : '');
      $tens = (int) ($totalSumLevelBlocks[$i] % 100);
      $singles = '';
      if ( $tens < 20 ) {
        $tens = ($tens ? ' and ' . $list1[$tens] . ' ' : '' );
      } elseif ($tens >= 20) {
        $tens = (int)($tens / 10);
        $tens = ' and ' . $list2[$tens] . ' ';
        $singles = (int) ($totalSumLevelBlocks[$i] % 10);
        $singles = ' ' . $list1[$singles] . ' ';
      }
      $sumWordsArray[] = $hundreds . $tens . $singles . ( ( $levelBlocks && ( int ) ( $totalSumLevelBlocks[$i] ) ) ? ' ' . $list3[$levelBlocks] . ' ' : '' );
    }

    $sumWords = implode(' ',  $sumWordsArray);

    $sumWords = preg_replace('/^\s\b(and)/', '', $sumWords );
    $sumWords = trim($sumWords);
    return ucwords($sumWords);
  }
}
