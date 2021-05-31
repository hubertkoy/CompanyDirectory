<template>
  <div>
    <button @click="goTop" ref="goTopBtn" id="goTopBtn" title="Go to top"><i class="fa fa-arrow-up"></i></button>
  </div>
</template>

<script>
export default {
  name: "topButton",
  methods: {
    // When user click on the button, scroll to the top of the document
    goTop() {
      document.body.scrollTop = 0;
      document.documentElement.scrollTop = 0;
    }
  },
  mounted() {
    // get btn element
    const btn = this.$refs.goTopBtn;
    //when user scrolls down 20px from top of the document, show the button
    window.onscroll = function () {
      scrollFunction()
    };

    function scrollFunction() {
      if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        btn.style.display = 'block';
      } else {
        btn.style.display = 'none';
      }
    }
  },
}
</script>

<style scoped>
#goTopBtn {
  display: none;
  position: fixed;
  bottom: 20px;
  right: 30px;
  z-index: 99;
  font-size: 18px;
  border: none;
  outline: none;
  background-color: #535353;
  color: white;
  cursor: pointer;
  padding: 15px;
  border-radius: 4px;
}

#goTopBtn:hover {
  background-color: #555;
}
</style>