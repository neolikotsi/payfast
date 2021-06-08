<?php


namespace NeoLikotsi\Payfast\Test\Features;

use NeoLikotsi\Payfast;
use NeoLikotsi\Payfast\Test\TestCase;

class ValidateSignatureTest extends TestCase
{
    protected $payfast;

    protected function setUp(): void
    {
        parent::setUp();

        $this->payfast = new Payfast;

        $this->payfast->setMerchant([
            'merchant_id' => '10000100',
            'merchant_key' => '46f0cd694581a',
            'return_url' => 'http://your-domain.co.za/success',
            'cancel_url' => 'http://your-domain.co.za/cancel',
            'notify_url' => 'http://your-domain.co.za/itn',
        ]);

        $this->payfast->setBuyer('Jane', 'Doe', 'jane@example.com');
        $this->payfast->setItem('item-title', 'description');
        $this->payfast->setMerchantReference(1);
    }


    /** @test */
    public function it_returns_valid_onsite_payment_signature()
    {
        /** generate signature on http://sandbox.payfast.co.za
         *  with POST CHECK tool
        */
        $sandboxGeneratedQueryString = 'merchant_id=10000100&merchant_key=46f0cd694581a&return_url=http%3A%2F%2Fyour-domain.co.za%2Fsuccess&cancel_url=http%3A%2F%2Fyour-domain.co.za%2Fcancel&notify_url=http%3A%2F%2Fyour-domain.co.za%2Fitn&name_first=Jane&name_last=Doe&email_address=jane%40example.com&m_payment_id=1&amount=100.50&item_name=item-title&item_description=description';
        $sandboxGeneratedSignature = '279d5d8fd4164b1f2fc17467afe4602b';

        $this->payfast->setAmount('100.50');
        $this->payfast->paymentForm();

        $packageQueryString = $this->payfast->buildQueryString(false);
        $packageSignature = md5($packageQueryString);

        $this->assertSame($sandboxGeneratedQueryString, $packageQueryString);
        $this->assertSame($sandboxGeneratedSignature, $packageSignature);
    }

    /** @test */
    public function it_returns_valid_requiring_billing_signature()
    {
         /** generate signature on http://sandbox.payfast.co.za
         *  with POST CHECK tool
        */
        $sandboxGeneratedQueryString = 'merchant_id=10000100&merchant_key=46f0cd694581a&return_url=http%3A%2F%2Fyour-domain.co.za%2Fsuccess&cancel_url=http%3A%2F%2Fyour-domain.co.za%2Fcancel&notify_url=http%3A%2F%2Fyour-domain.co.za%2Fitn&name_first=Jane&name_last=Doe&email_address=jane%40example.com&m_payment_id=1&amount=100.50&item_name=item-title&item_description=description&subscription_type=1&frequency=3&cycles=12';
        $sandboxGeneratedSignature = '9b8249c60b255e0644d35cdc80f649d2';

        $this->payfast->setIsSubscription(true);
        $this->payfast->setFrequency(3);
        $this->payfast->setCycles(12);
        $this->payfast->setAmount(100.50);

        $paymentForm = $this->payfast->paymentForm();

        $packageQueryString = $this->payfast->buildQueryString(false);
        $packageSignature = md5($packageQueryString);

        $this->assertSame($sandboxGeneratedQueryString, $packageQueryString);
        $this->assertSame($sandboxGeneratedSignature, $packageSignature);

        $this->assertStringContainsStringIgnoringCase('<input type="hidden" name="subscription_type" value="1">', $paymentForm);
    }

}
