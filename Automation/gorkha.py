import schedule
import time
import os
from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.common.action_chains import ActionChains
from selenium.common.exceptions import TimeoutException, NoSuchElementException


def job():
    # set the full path

    download_directory = os.path.join(os.getcwd(), "gorkhafriday")

    options = webdriver.ChromeOptions()
    prefs = {
        "download.default_directory": download_directory,
        "plugins.always_open_pdf_externally": True,
    }
    options.add_experimental_option("prefs", prefs)

    driver = webdriver.Chrome(options=options)
    driver.get("https://epaper.gorkhapatraonline.com/single/friday-suppliment")

    try:
        # wait for the odf links to be present
        pdf_link = WebDriverWait(driver, 10).until(
            EC.presence_of_all_elements_located(
                (By.XPATH, "//a[contains(@href,'.pdf')]")
            )
        )

        for pdf_link in pdf_link:
            try:
                # open the link in a new tab

                ActionChains(driver).key_down(Keys.CONTROL).click(pdf_link).key_up(
                    Keys.CONTROL
                ).perform()

                # switch to the new tab

                driver.switch_to.window(driver.window_handles[-1])

                # wait for the download butoon to visible

                download_button = WebDriverWait(driver, 10).until(
                    EC.visibility_of_element_located(
                        (By.XPATH, "//button[@id='download' and @title='Download']")
                    )
                )
                ActionChains(driver).move_to_element(download_button).click().perform()

                # allow some time for the download to complete
                time.sleep(15)

            except (TimeoutException, NoSuchElementException) as e:
                print(f"Error processing PDF links: {e}")

            finally:
                driver.close()

                # switch back to the main  tab
                driver.switch_to.window(driver.window_handles[0])

    finally:
        driver.quit()


# Execute the function directly



def delete_pdf_files():
    chrome_options = Options()
    # chrome_options.add_argument("--headless")
    driver2 = webdriver.Chrome(options=chrome_options)

    pdf_file_extension = ".pdf"
    cwd = os.getcwd()
    pdf_files = [f for f in os.listdir(cwd) if f.endswith(pdf_file_extension)]
    if pdf_files:

        # del all pdf files that were downloaded

        for pdf_file in pdf_files:
            file_path = os.path.join(cwd, pdf_file)
            os.remove(file_path)
            print("PDF file deleted:", file_path)

        else:
            print("No PDF Files found in the current working directory")

    driver2.quit()

    # run above code on certain time


schedule.every().day.at("08:15").do(job)

# sechdule time to to del the downloaded pdf

# schedule.every().day.at("23:00").do(delete_pdf_files)

while True:
    schedule.run_pending()
    time.sleep(1)
