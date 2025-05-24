<?php 
include 'partials/header.php';

// Fetch featured posts for hero slider (latest 3)
$featured_slider_query = "SELECT * FROM posts ORDER BY date_time DESC  ";
$featured_slider_result = mysqli_query($connection, $featured_slider_query);

// Fetch featured posts for the new carousel (is_featured=1)
$featured_carousel_query = "SELECT * FROM posts WHERE is_featured=1 ORDER BY date_time DESC";
$featured_carousel_result = mysqli_query($connection, $featured_carousel_query);

// Fetch non-featured posts for posts carousel (9 posts)
$query = "SELECT * FROM posts ORDER BY date_time DESC";
$posts = mysqli_query($connection, $query);
?>
<!-- Welcome Modal -->
<div id="welcomeModal" class="modal">
  <div class="modal-content">
    <span class="close-btn" id="closeModal">&times;</span>
     <img src="<?= ROOT_URL ?>logo.png" alt="harsh" class="logo-img">
    <h2>Welcome to WriteVibes!</h2>
    <p>Welcome to WriteVibes!

Discover a vibrant community where you can explore inspiring blogs, express your thoughts by writing your own stories, and connect with readers just like you. Sign up today to start sharing your voice, engage with fellow writers, and dive into a world of captivating content. Your next great read or write adventure awaits—let’s make your words shine!</p>
   <br><button onclick="document.getElementById('welcomeModal').style.display='none';"
      style="background: #ff4081; border: none; padding: 12px 30px; color: white; font-weight: bold; font-size: 1rem; border-radius: 25px; cursor: pointer; transition: background 0.3s ease;">
      Explore WriteVibes
    </button><br>
    <small>Copyright &copy; VibeWrites By Kumar Harshvardhan</small>
  </div>
  
</div>

<style>
.modal {
  display: none; /* Hidden by default */
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.6);
  justify-content: center;
  align-items: center;
}

.modal-content {
  background-color: #222;
  color: #eee;
  margin: auto;
  padding: 20px 30px;
  border-radius: 8px;
  width: 90%;
  max-width: 400px;
  text-align: center;
  box-shadow: 0 0 15px #0ff;
  font-family: 'Arial', sans-serif;
  position: relative;
}

.close-btn {
  color: #aaa;
  position: absolute;
  right: 15px;
  top: 12px;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
  transition: color 0.3s ease;
}

.close-btn:hover {
  color: #0ff;
}
</style>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('welcomeModal');
    const closeBtn = document.getElementById('closeModal');

    // Show modal on page load
    modal.style.display = 'flex';

    // Close modal on clicking close button
    closeBtn.onclick = () => {
      modal.style.display = 'none';
    };

    // Close modal if clicking outside content
    window.onclick = (event) => {
      if (event.target === modal) {
        modal.style.display = 'none';
      }
    };
  });
</script>

<!-- Hero Slider -->
<?php if (mysqli_num_rows($featured_slider_result) > 0): ?>
<section class="hero-slider">
  <div class="swiper heroSwiper">
    <div class="swiper-wrapper">
      <?php while ($slide = mysqli_fetch_assoc($featured_slider_result)):
        $category_query = "SELECT * FROM categories WHERE id={$slide['category_id']}";
        $category_result = mysqli_query($connection, $category_query);
        $category = mysqli_fetch_assoc($category_result);

        $author_query = "SELECT * FROM users WHERE id={$slide['author_id']}";
        $author_result = mysqli_query($connection, $author_query);
        $author = mysqli_fetch_assoc($author_result);
      ?>
      <div class="swiper-slide hero-slide" style="background-image: url('./images/<?= htmlspecialchars($slide['thumbnail']) ?>');">
        <div class="hero-slide-content">
          <a href="category-posts.php?id=<?= $slide['category_id'] ?>" class="category__button"><?= htmlspecialchars($category['title']) ?></a>
          <h2><a href="post.php?id=<?= $slide['id'] ?>"><?= htmlspecialchars($slide['title']) ?></a></h2>
          <p><?= substr(html_entity_decode($slide['body']), 0, 150) ?>...</p>
          <div class="author-info">
            <img src="./images/<?= htmlspecialchars($author['avatar']) ?>" alt="Author avatar" />
            <span>By <?= htmlspecialchars($author['firstname'] . ' ' . $author['lastname']) ?></span>
            <small><?= date("M d, Y - H:i", strtotime($slide['date_time'])) ?></small>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
    <div class="swiper-pagination"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
  </div>
</section>
<?php endif; ?>
<?php
// Fetch latest 5 posts for news ticker
$news_ticker_query = "SELECT id, title FROM posts ORDER BY date_time DESC LIMIT 5";
$news_ticker_result = mysqli_query($connection, $news_ticker_query);
?>

<?php if (mysqli_num_rows($news_ticker_result) > 0): ?>
<section class="news-ticker" style="margin-top: 0;">
  <div class="ticker-wrapper">
    <div class="ticker-content">
      <span style="margin-right: 30px; font-weight: 700;">Check the new post:</span>
      <?php 
      $news_items = [];
      while ($news = mysqli_fetch_assoc($news_ticker_result)) {
          $title = htmlspecialchars($news['title']);
          $link = "post.php?id=" . $news['id'];
          $news_items[] = "<a href=\"$link\">$title</a>";
      }
      echo implode(' &nbsp;&bull;&nbsp; ', $news_items);
      ?>
    </div>
  </div>
</section>
<?php endif; ?>
<!-- Featured Posts Carousel -->
<?php if (mysqli_num_rows($featured_carousel_result) > 0): ?>
<section class="featured-posts-carousel">
  <div class="container">
    <h2 class="section-title">Featured Posts</h2>
    <div class="swiper featuredPostsSwiper">
      <div class="swiper-wrapper">
        <?php while ($post = mysqli_fetch_assoc($featured_carousel_result)) : 
          $category_query = "SELECT * FROM categories WHERE id={$post['category_id']}";
          $category_result = mysqli_query($connection, $category_query);
          $category = mysqli_fetch_assoc($category_result);

          $author_query = "SELECT * FROM users WHERE id={$post['author_id']}";
          $author_result = mysqli_query($connection, $author_query);
          $author = mysqli_fetch_assoc($author_result);
        ?>
          <article class="post swiper-slide">
            <div class="post__thumbnail" style="width: 300px; height: 200px;">
              <img src="./images/<?= htmlspecialchars($post['thumbnail']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" />
            </div>
            <div class="post__info">
              <a href="<?= ROOT_URL ?>category-posts.php?id=<?= $post['category_id'] ?>" class="category__button"><?= htmlspecialchars($category['title']) ?></a>
              <h2 class="post__title"><a href="<?= ROOT_URL ?>post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></h2>
              <a href="<?= ROOT_URL ?>post.php?id=<?= $post['id'] ?>">
                <p class="post__body" style="min-height: 100px;"><?= substr(html_entity_decode($post['body']), 0, 150) ?>...</p>
              </a>
              <div class="post__author">
                <div class="post__author-avatar">
                  <img src="./images/<?= htmlspecialchars($author['avatar']) ?>" alt="<?= htmlspecialchars($author['firstname']) ?>" />
                </div>
                <div class="post__author-info">
                  <h5>By: <?= htmlspecialchars($author['firstname'] . ' ' . $author['lastname']) ?></h5>
                  <small><?= date("M d, Y - H:i", strtotime($post['date_time'])) ?></small>
                </div>
              </div>
            </div>
          </article>
        <?php endwhile; ?>
      </div>
       <br>
      <div class="swiper-pagination"></div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>
  </div>
</section>
<?php endif; ?>
<section class="circular-categories">
  <div class="container">
    <h2 class="section-title">Categories</h2>

    <div class="slider-wrapper">
      <button class="slider-arrow left">&#10094;</button>

      <div class="circular-categories-container" id="categorySlider">
        <?php 
        $all_categories_query = "SELECT * FROM categories";
        $all_categories_result = mysqli_query($connection, $all_categories_query);
        $categories = [];
        while ($category = mysqli_fetch_assoc($all_categories_result)) {
          $categories[] = $category;
        }
        $loopCategories = array_merge(array_slice($categories, -3), $categories, array_slice($categories, 0, 3));
        foreach ($loopCategories as $category): ?>
          <a href="<?= ROOT_URL ?>category-posts.php?id=<?= $category['id'] ?>" class="circular-category">
            <div class="category-icon" style="background-image: url('<?= htmlspecialchars($category['icon']) ?>');"></div>
            <span class="category-name"><?= htmlspecialchars($category['title']) ?></span>
          </a>
        <?php endforeach; ?>
      </div>

      <button class="slider-arrow right">&#10095;</button>
    </div>
  </div>
</section>

<script>
  const slider = document.getElementById('categorySlider');
const items = slider.children;
const visibleCount = 3;
const scrollItemWidth = items[0].offsetWidth + 24;

// Start in the "real" content
const startIndex = 3;
slider.scrollLeft = startIndex * scrollItemWidth;

function loopScroll(dir) {
  slider.scrollBy({ left: dir * scrollItemWidth, behavior: 'smooth' });

  setTimeout(() => {
    const totalItems = items.length;
    const currentIndex = Math.round(slider.scrollLeft / scrollItemWidth);

    if (currentIndex <= 2 && dir < 0) {
      slider.scrollLeft = (totalItems - visibleCount * 2 + currentIndex) * scrollItemWidth;
    } else if (currentIndex >= totalItems - visibleCount && dir > 0) {
      slider.scrollLeft = (visibleCount + (currentIndex - (totalItems - visibleCount))) * scrollItemWidth;
    }
  }, 300); // wait for smooth scroll
}

document.querySelector('.slider-arrow.left').onclick = () => loopScroll(-1);
document.querySelector('.slider-arrow.right').onclick = () => loopScroll(1);

// ** Autoplay logic **
let autoplayInterval = setInterval(() => {
  loopScroll(1);
}, 3000);  // Change 3000 to speed in ms

// Optional: Pause autoplay on mouse enter, resume on mouse leave
slider.parentElement.addEventListener('mouseenter', () => clearInterval(autoplayInterval));
slider.parentElement.addEventListener('mouseleave', () => {
  autoplayInterval = setInterval(() => {
    loopScroll(1);
  }, 3000);
});

</script>
<!-- Swiper CSS -->
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"
/>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

<section class="spotlight-section">
  <div class="container">
    <h2 class="spotlight-title">Spotlight</h2>
    <div class="swiper spotlight-swiper">
      <div class="swiper-wrapper">
        <?php
        $spotlight_query = "SELECT * FROM posts WHERE is_spotlight = 1 ORDER BY date_time DESC";
        $spotlight_result = mysqli_query($connection, $spotlight_query);
        while ($post = mysqli_fetch_assoc($spotlight_result)) :
        ?>
          <div class="swiper-slide spotlight-item">
            <div class="spotlight-background"></div>
            <div class="spotlight-thumb" style="background-image: url('<?= ROOT_URL ?>images/<?= htmlspecialchars($post['thumbnail']) ?>');"></div>
            <div class="spotlight-info">
              <h3><?= htmlspecialchars($post['title']) ?></h3>
              <p><?= htmlspecialchars(substr($post['body'], 0, 100)) ?>...</p>
              <a href="<?= ROOT_URL ?>post.php?id=<?= $post['id'] ?>" class="read-more">Read More</a>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
      <!-- Arrows -->
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>
  </div>
</section>
<script>
  const spotlightSwiper = new Swiper('.spotlight-swiper', {
    loop: true,
    slidesPerView: 1,
    spaceBetween: 20,
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
      autoplay: {
    delay: 3000,       // 3 seconds per slide
    disableOnInteraction: false, // autoplay continues after user interaction
  },
    breakpoints: {
      768: { slidesPerView: 2 },
      1024: { slidesPerView: 3 },
    },
  });
</script>


<section class="circular-categories" >
  <div class="container">
    <h2 class="section-title">Authors</h2>

    <div class="swiper mySwiper">
      <div class="swiper-wrapper">
        <?php 
        $all_users_query = "SELECT * FROM users";
        $all_users_result = mysqli_query($connection, $all_users_query);
        while ($user = mysqli_fetch_assoc($all_users_result)): ?>
          <a href="<?= ROOT_URL ?>user-posts.php?id=<?= $user['id'] ?>" class="swiper-slide circular-category">
            <div class="category-icon" style="background-image: url('images/<?= htmlspecialchars($user['avatar']) ?>');"></div>
            <span class="category-name"><?= htmlspecialchars($user['firstname']) ?></span>
          </a>
        <?php endwhile; ?>
      </div>

      <!-- Navigation buttons -->
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>
  </div>
</section>




<!-- Recent Posts Carousel -->
<section class="posts-carousel <?= mysqli_num_rows($featured_slider_result) == 0 ? 'section__extra-margin' : '' ?>">
  <div class="container">
    <h2 class="section-title">Recent Posts</h2>
    <div class="swiper postsSwiper">
      <div class="swiper-wrapper">
        <?php while ($post = mysqli_fetch_assoc($posts)) : 
          $category_query = "SELECT * FROM categories WHERE id={$post['category_id']}";
          $category_result = mysqli_query($connection, $category_query);
          $category = mysqli_fetch_assoc($category_result);

          $author_query = "SELECT * FROM users WHERE id={$post['author_id']}";
          $author_result = mysqli_query($connection, $author_query);
          $author = mysqli_fetch_assoc($author_result);
        ?>
          <article class="post swiper-slide">
            <div class="post__thumbnail" style="width: 300px; height: 200px;">
              <img src="./images/<?= htmlspecialchars($post['thumbnail']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" />
            </div>
            <div class="post__info">
              <a href="<?= ROOT_URL ?>category-posts.php?id=<?= $post['category_id'] ?>" class="category__button"><?= htmlspecialchars($category['title']) ?></a>
              <h2 class="post__title"><a href="<?= ROOT_URL ?>post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></h2>
              <a href="<?= ROOT_URL ?>post.php?id=<?= $post['id'] ?>">
                <p class="post__body" style="min-height: 100px;"><?= substr(html_entity_decode($post['body']), 0, 150) ?>...</p>
              </a>
              <div class="post__author">
                <div class="post__author-avatar">
                  <img src="./images/<?= htmlspecialchars($author['avatar']) ?>" alt="<?= htmlspecialchars($author['firstname']) ?>" />
                </div>
                <div class="post__author-info">
                  <h5>By: <?= htmlspecialchars($author['firstname'] . ' ' . $author['lastname']) ?></h5>
                  <small><?= date("M d, Y - H:i", strtotime($post['date_time'])) ?></small>
                </div>
              </div>
            </div>
          </article>
        <?php endwhile; ?>
      </div>
      <br>
      <div class="swiper-pagination"></div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>
  </div>
</section>

<!-- Categories Buttons -->


<script>
  const swiper = new Swiper('.mySwiper', {
  slidesPerView: 3,
  spaceBetween: 5,
  loop: true,
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },
  autoplay: {
    delay: 3000,
    disableOnInteraction: false,
  },
  breakpoints: {
    640: {
      slidesPerView: 2,
      spaceBetween: 8,
    },
    1024: {
      slidesPerView: 3,
      spaceBetween: 12,
    },
  },
});
</script>

<?php include './partials/footer.php'; ?>

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

<!-- Swiper Initialization -->
<script>
const heroSwiper = new Swiper('.heroSwiper', {
  slidesPerView: 1,
  spaceBetween: 10,
  loop: true,
  pagination: {
    el: '.heroSwiper .swiper-pagination',
    clickable: true,
  },
  navigation: {
    nextEl: '.heroSwiper .swiper-button-next',
    prevEl: '.heroSwiper .swiper-button-prev',
  },
  autoplay: {
    delay: 5000,
    disableOnInteraction: false,
  }
});

const featuredPostsSwiper = new Swiper('.featuredPostsSwiper', {
  slidesPerView: 3,
  spaceBetween: 20,
  loop: true,  // loop enabled for smooth autoplay
  navigation: {
    nextEl: '.featuredPostsSwiper .swiper-button-next',
    prevEl: '.featuredPostsSwiper .swiper-button-prev',
  },
  pagination: {
    el: '.featuredPostsSwiper .swiper-pagination',
    clickable: true,
  },
  autoplay: {
    delay: 5000,
    disableOnInteraction: false,
  },
  breakpoints: {
    320: { slidesPerView: 1 },
    768: { slidesPerView: 2 },
    1024: { slidesPerView: 3 }
  }
});

const postsSwiper = new Swiper('.postsSwiper', {
  slidesPerView: 3,
  spaceBetween: 20,
  loop: true,  // loop enabled for smooth autoplay
  navigation: {
    nextEl: '.postsSwiper .swiper-button-next',
    prevEl: '.postsSwiper .swiper-button-prev',
  },
  pagination: {
    el: '.postsSwiper .swiper-pagination',
    clickable: true,
  },
  autoplay: {
    delay: 5000,
    disableOnInteraction: false,
  },
  breakpoints: {
    320: { slidesPerView: 1 },
    768: { slidesPerView: 2 },
    1024: { slidesPerView: 3 }
  }
});

</script>
