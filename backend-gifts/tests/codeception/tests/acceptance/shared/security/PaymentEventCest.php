<?php
namespace shared;
use Step\Acceptance\DonationsBackendTester;
use \AcceptanceTester;

/**
 * TODO: This test should be moved to Donations backend test suite.
 *
 * Class PaymentEventCest
 * @package shared
 *
 * @env chrome
 */
class PaymentEventCest {

  /**
   * Change data in Test stripe account, run cron and check updates in Security report.
   *
   * @param \Step\Acceptance\DonationsBackendTester $I
   * @group backend-donations
   *
   * Stripe added captcha to the login page, there is no way to run this test now.
   * @skip
   */
  public function stripeSecurityReport(DonationsBackendTester $I) {
    // Prepares unique suffix.
    $uniqueSuffix = date('c');

    // Saves Drupal URL.
    $I->amOnPage('/');
    $currentURL = $I->executeJS('return location.origin;');

    // Login in Stripe and make a test change.
    $this->makeChangeInStripe($I, $uniqueSuffix);

    // Restore Drupal URL.
    $I->amOnUrl($currentURL);

    // Login as user with Administrator role.
    $I->login('administrator.test');

    // Run cron.
    $I->amGoingTo('Updates Payment events records');
    $I->amOnPage('/cw-payment-event/run-cron/P1-fmeiWUyCkf5wgv3Y97jNAHEjS92BkheQejNEwAXU0Esi5Bl_ts6TM1CEI8XDd0ErNsO0XZA1');
    $I->wait(5);

    // Go to Security report.
    $I->amGoingTo('Check information in Payment security report');
    $I->amOnPage('/admin/payment-security-report');

    // Make sure report contains changes from Stripe.
    $I->waitForText('Payment security report',  5);
    $I->see("Changed your account's statement descriptor to Test Descriptor " . $uniqueSuffix, '.view-payment-security-report .views-field-field-stripe-event-description');
  }

  /**
   * Login in Stripe and make a test change.
   *
   * Stripe frontend tests work only in Chrome environment.
   *
   * THIS METHOD IS HELPER AND NOT EXECUTED BY TEST SUITE.
   */
  private function makeChangeInStripe(AcceptanceTester $I, $uniqueSuffix) {
    $stripeTestUsername = 'developer-stripe-test@systemseed.com';
    $stripeTestPassword = 'C0nc3rn!';

    // Go to Stripe Dashboard login page and login to Test account.
    $I->amOnUrl('https://dashboard.stripe.com/login');
    $I->waitForElement(".login button[type='submit']", 10);
    $I->submitForm('#main-body form', ['email' => $stripeTestUsername, 'password' => $stripeTestPassword]);

    $I->waitForText('Business settings', 10, '.db-Sidebar-link--settings');

    // Go to Account settings page.
    $I->amOnUrl('https://dashboard.stripe.com/account');
    $I->waitForText('Public information', 10);

    // Update Statement description field.
    $I->fillField('#statement_descriptor', 'Test Descriptor ' . $uniqueSuffix);
    // Current version of Stripe UI contains only one button with "submit" type.
    $I->click("button[type='submit']");
    $I->waitForText('Your changes have been saved', 10);
  }
}