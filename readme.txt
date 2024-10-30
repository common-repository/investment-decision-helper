=== Investment Decision Helper ===
Contributors: anibalealvarezs
Donate link: http://calculadorafinanciera.co
Tags: calculator, widget, shortcode, finance, financing, payments, investments, bonds, amortization, price, interest, coupon, internal, rate, return, financial
Requires at least: 3.8
Tested up to: 3.9
Stable tag: 1.1.1
License: Creative Commons
License URI: http://creativecommons.org/licenses/by/3.0/

This tool will allow you to compare return rates of two different custom instruments in order to help you taking the best decision..

== Description ==

You can create custom investments adding: Starting Expenditures, regular income in percentage (coupon), amortization at the end (useful for Bonds), payments frequency and price of the instruments in the secondary market .

Investment Decision Helper will calculate and compare the return rates, and help you make the right choice.

Note: Version 1.0.2 will still be available for those who are unwilling to add the additional Payments Frequency option. In any case, the Annual Payments scheme will show the same behaviour for the instruments.

== Installation ==

1. Upload to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Add to a sidebar using the 'Widgets' menu in WordPress or by placing '[investment_decision]' in the content area of a page/post.

== Screenshots ==

1. Designed to be used in sidebars or 320px max-width columns

== Usage ==

1. Enter the cost of the investment.
2. Enter the coupon or periodic income rate (interest rate).
3. Enter the number of times the interest will be received.
4. (Optional) Enter de last amortization in case you need it (You can assume 100% for Bonds, otherwise omit it).
5. (Optional) Enter de Price of the instrument in percentage in case it is negotiated in secondary markets.
6. (Optional) Select the income frequency. Base returns for instruments will be considered "Annual" unless you select a different option.
7. (Optional) For pre-calculated return, place the corresponding Return rate. It will override every information added in previous cells (except "Frecuency"). This option won't be placed in the chart since there're no "Starting Expenditure" or "Coupon" to obtain the corresponding "NPV".
8. Click on "submit" button and look at the new box showing the return rates of both instruments. The preferred option will be featured in green, and a chart will show you the NPV behavior as IRR changes.

== Changelog ==

= 1.1.1 =
* Fixed error in image URL

= 1.1 =
* Payments Frequency enabled
* Every instruments will be automatically transformed into their annual equivalents for comparison to be possible

= 1.0.2 =
* Added translation files for Spanish and English. Investment Decision Helper will show strings according to wordpress installation language
* Fixed X axis tags in chart (Auto-adjust)

= 1.0.1 =
* Added more values to chart for curves to be more accurate
* Some changes in front end's style for an easier form usage

= 1.0 =
* Initial release