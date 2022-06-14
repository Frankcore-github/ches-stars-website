<template>
  <div>
    <section class="bgimage">
      <div class="container text-center text-white headerset">
        <h1>Ches' Stars Secondary School</h1>
        <h2>
          <div class="console-container">
            <span id="text"></span>
            <div class="console-underscore" id="console">&#95;</div>
          </div>
        </h2>
      </div>
      <div>
        <marquee
          class="home-banner"
          onmouseover="this.stop();"
          onmouseout="this.start();"
          behavior="scroll"
          direction="left"
          scrollamount="3"
        >
          <a
            class="pr-4"
            v-for="newsfeed in this.newsfeed"
            :key="newsfeed.id"
            :href="`/storage/file/newsfeed/${newsfeed.file_name}`"
            ><img src="/storage/img/new.GIF" width="25" height="20" />&ensp;{{
              newsfeed.file_caption
            }}</a
          >
        </marquee>
      </div>
    </section>
    <!-- <section class="text-center container-fluid py-5">
      <div class="img-thumbnail"><img src="/storage/img/classx2021.jpg" class="img-fluid rounded" alt="Responsive image"/></div>
    </section> -->
    <section class="pt-3 container custom-header">
      <h1 class="text-center">Principal's desk</h1>
      <div class="box-outer">
        <img
          src="/storage/img/principal.jpg"
          alt="Principal-img"
          class="img-fluid"
        />
        <div class="text">
          <div>
            <span class="innertext"></span>
          </div>
        </div>
      </div>
      <div>
        <p>
          In 1991, when the school was founded, it was just a humble beginning.
          A school where parents would be proud of sending their children. We
          focus on a holistic development of a complete personality and
          well-rounded education, with the creation of a space where children
          would be able to grow, learn, and develop their skills.
        </p>
        <p>
          The journey of many thousands steps, from kindergarten through middle
          school to secondary school, is probably the most important in the life
          of an individual. It is where values are learnt, habits are adopted,
          and knowledge imbibed. We as a school pride ourselves in sculpting of
          young minds that parents and guardians over these past 28 years have
          entrusted us with. As the principal, I consider it my calling and an
          immense responsibility to lead.
        </p>
        <p>
          The school was founded based on biblical principles, yet we honour and
          respect the belief of other faith, and as such we emphasize the value
          of love, humility, commitment and discipline as the driving factors
          towards success and achievements.
        </p>
        <p>
          In 2019 and beyond, the challenges that a changing economy and an
          accelerated technology present are manifold. As an institute of
          learning, we would not be doing our job if we did not try and
          improvise the introduction of a short-term vocational training course,
          the purpose of which the training of students in entrepreneurship, is
          our agenda.
        </p>
        <p>
          Our Alumni network grows every year and it is a source of pride that
          our students are now professional in many diverse career
        </p>
        <p>
          I am optimistic that the next 28 years will only be a continuation of
          our journey. A journey that is dedicated to the quality education of
          students.
        </p>
      </div>
    </section>
    <section class="pt-2 container">
      <div class="row">
        <div
          class="col-lg-8 card m-3 text-selection-none"
          style="height: 250px; overflow: hidden"
        >
          <div class="card-header"><span>Notice</span> <button type="button" class="btn btn-outline-primary btn-sm float-right" data-toggle="modal" data-target="#NoticeModal"><i class="fas fa-list-ol"></i> View all</button></div>
          <div class="h-100">
            <marquee
              class="h-100"
              onmouseover="this.stop();"
              onmouseout="this.start();"
              behavior="scroll"
              align="left"
              direction="up"
              scrollamount="2"
            >
              <ol>
                <li v-for="notice in this.notice" :key="notice.id">
                  <span v-html="notice.message"></span>
                </li>
              </ol>
            </marquee>
          </div>
        </div>
        <div class="col card m-3 text-selection-none" style="height: 250px">
          <div class="card-header"><span>News feed</span> <button type="button" class="btn btn-outline-primary btn-sm float-right" data-toggle="modal" data-target="#NewsFeedModal"><i class="fas fa-list-ol"></i> View all</button></div>
          <div class="h-100">
            <marquee
              class="h-100"
              onmouseover="this.stop();"
              onmouseout="this.start();"
              behavior="scroll"
              align="left"
              direction="up"
              scrollamount="3"
            >
              <ol>
                  <li v-for="newsfeed in this.newsfeed" :key="newsfeed.id">
                  <a :href="`/storage/file/newsfeed/${newsfeed.file_name}`">{{
                    newsfeed.file_caption
                  }}</a>
                </li>
              </ol>
            </marquee>
          </div>
        </div>
      </div>
    </section>

    

    <!-- <section>
      <div class="modal fade" id="admissionModal" tabindex="-1" role="dialog" aria-labelledby="admissionModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="admissionModalLongTitle">
                Admission 2021
              </h5>
              <button
                type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body text-white text-center" style=";background-image: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.6)), url('/storage/img/modal.jpg');  background-size: cover !important; min-height: 20em;">
                <h1 class="font-weight-bold m-5">ADMISSION OPEN</h1>
                <h3 class="font-weight-bold m-auto">Class XI - Arts Stream</h3>
            </div>
            <div class="modal-footer justify-content-center">
              <button @click="applyNow()" type="button" class="btn btn-outline-primary btn-block mx-5" style="border-radius:23px;">
                Apply Now
              </button>
            </div>
          </div>
        </div>
      </div>
    </section> -->
    <section class="notice-board-modal">
      <!-- Modal -->
      <div class="modal fade"
        id="NoticeModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="NoticeModalCenterTitle"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h6 class="modal-title" id="NoticeModalLongTitle">
               Notice Board
              </h6>
              <button
                type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" style="height:400px;overflow-y: auto;">
               <ol>
                  <li v-for="notice in this.notice" :key="notice.id">
                  <span v-html="notice.message"></span>
                </li>
              </ol>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="news-feed-modal">
      <!-- Modal -->
      <div class="modal fade"
        id="NewsFeedModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="NewsFeedModalCenterTitle"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h6 class="modal-title" id="NewsFeedModalLongTitle">
                News feed
              </h6>
              <button
                type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" style="height:400px;overflow-y: auto;">
                <ol>
                  <li v-for="newsfeed in this.newsfeed" :key="newsfeed.id">
                  <a :href="`/storage/file/newsfeed/${newsfeed.file_name}`">{{
                    newsfeed.file_caption
                  }}</a>
                </li>
              </ol>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  data() {
    return {
      notice: {},
      newsfeed: {},
    };
  },
  methods: {
    load() {
      axios
        .get("/api/home/")
        .then((response) => {
          (this.notice = response.data.notice),
            (this.newsfeed = response.data.newsfeed);
        })
        .catch((e) => {
          console.log(e);
        });
    },
    // applyNow(){
    //     $('#admissionModal').modal('hide');
    //     this.$router.push('/onlineadmission');
    // }
  },
  mounted() {
    this.load();
    // $('#admissionModal').modal('show');
    consoleText(['"TO LEARN TO SHINE"'], "text", ["white"]);

    function consoleText(words, id, colors) {
      if (colors === undefined) colors = ["#fff"];
      var visible = true;
      var con = document.getElementById("console");
      var letterCount = 1;
      var x = 1;
      var waiting = false;
      var target = document.getElementById(id);
      target.setAttribute("style", "color:" + colors[0]);
      window.setInterval(function () {
        if (letterCount === 0 && waiting === false) {
          waiting = true;
          target.innerHTML = words[0].substring(0, letterCount);
          window.setTimeout(function () {
            var usedColor = colors.shift();
            colors.push(usedColor);
            var usedWord = words.shift();
            words.push(usedWord);
            x = 1;
            target.setAttribute("style", "color:" + colors[0]);
            letterCount += x;
            waiting = false;
          }, 1000);
        } else if (letterCount === words[0].length + 1 && waiting === false) {
          waiting = true;
          window.setTimeout(function () {
            x = -1;
            letterCount += x;
            waiting = false;
          }, 5000);
        } else if (waiting === false) {
          target.innerHTML = words[0].substring(0, letterCount);
          letterCount += x;
        }
      }, 120);
      window.setInterval(function () {
        if (visible === true) {
          con.className = "console-underscore hidden";
          visible = false;
        } else {
          con.className = "console-underscore";

          visible = true;
        }
      }, 400);
    }
    var images = ["banner1.jpg", "banner2.jpg", "background-home.jpg"];
    var i = 0;
    $(".bgimage").css(
      "background-image",
      "linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(/storage/img/" +
        images[i] +
        ")"
    );
    setInterval(function () {
      i++;
      if (i == images.length) {
        i = 0;
      }
      $(".bgimage").fadeOut("slow", function () {
        $(this).css(
          "background-image",
          "linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(/storage/img/" +
            images[i] +
            ")"
        );
        $(this).fadeIn("slow");
      });
    }, 6000);
  },
};
</script>
