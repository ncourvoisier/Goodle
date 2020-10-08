// Generated by Selenium IDE
import org.junit.Test;
import org.junit.Before;
import org.junit.After;
import static org.junit.Assert.*;
import static org.hamcrest.CoreMatchers.is;
import static org.hamcrest.core.IsNot.not;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;

import org.openqa.selenium.chrome.ChromeDriver;

import org.openqa.selenium.firefox.FirefoxDriver;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;
import org.openqa.selenium.remote.RemoteWebDriver;
import org.openqa.selenium.remote.DesiredCapabilities;
import org.openqa.selenium.Dimension;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.interactions.Actions;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;
import org.openqa.selenium.JavascriptExecutor;
import org.openqa.selenium.Alert;
import org.openqa.selenium.Keys;
import java.util.*;
import java.net.MalformedURLException;
import java.net.URL;
public class ConnexionTest {
  private HtmlUnitDriver driver;
  private Map<String, Object> vars;
  JavascriptExecutor js;
  @Before
  public void setUp() {
    driver = new HtmlUnitDriver();
    js = (JavascriptExecutor) driver;
    vars = new HashMap<String, Object>();
  }
  @After
  public void tearDown() {
    driver.quit();
  }
  @Test
  public void connexion() {
    driver.get("http://m2gl.deptinfo-st.univ-fcomte.fr/~m2test3/preprod/index.php");
    driver.manage().window().setSize(new Dimension(550, 692));
    driver.findElement(By.linkText("ici")).click();
    driver.findElement(By.name("email")).click();
    driver.findElement(By.name("email")).sendKeys("testadmin@test.com");
    driver.findElement(By.name("password")).sendKeys("12345678");
    driver.findElement(By.name("password")).sendKeys(Keys.ENTER);
    driver.findElement(By.cssSelector("button")).click();
  }
}