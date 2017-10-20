<?php
namespace shared\frontend;
use Step\Acceptance\FrontendTester;

/**
 * Class CorporateGiftsCest
 * @package shared\frontend
 *
 * @env chrome
 * @group frontend-gifts
 */
class CorporateGiftsCest {

  /**
   * Open corporate gift page and make sure core functions are working.
   *
   * @param \Step\Acceptance\FrontendTester $I
   */
  public function corporatePage(FrontendTester $I) {
    $currency = $I->getCurrency();
    $gift = $I->getCorporateGiftData();

    $I->amGoingTo('Visit corporate gifts section');
    $I->amOnPage('/corporate');

    // Banner.
    $I->canSeeElement('.featured-image');

    // Wait until products are loaded.
    $I->waitForText('Corporate Gifts - Give the gift of hope to children this year', 15);

    $I->canSee('How Corporate Gifts work?');
    $I->canSee('1: Order');
    $I->canSee('2: What you get');
    $I->canSee('3: Additional benefit');
    $I->canSee('4: Hope');

    $I->amGoingTo('Visit corporate gift detailed page.');
    $I->wait(1);
    $I->click($gift['title']);

    $I->expectTo('See correct price');
    $I->canSee($gift['price'][$currency]['formatted']);
    $I->expectTo('See correct annotation');
    $I->waitForText($gift['annotation']);
    $I->expectTo('See correct description');
    $I->canSee($gift['description']);

    // Test "Add to basket" button.
    $I->clickAddToBasket();
    // Test "Buy now" button.
    $I->clickBuyNow();


    $I->expectTo('See Shopping Basket with two items of test product added');
    $I->canSee('Shopping Basket');
    $I->canSee($gift['title']);
    $I->canSee($gift['price'][$currency]['formatted']);
    $I->canSeeInPageSource('<span class="quantity">2</span>');
  }
}
