gsap.registerPlugin(SplitText);
let split = SplitText.create(".purple", { type: "chars" });
let split1 = SplitText.create(".span1", { type: "chars, words" });
let split2 = SplitText.create(".span2", { type: "chars, words" });
let split3 = SplitText.create(".span3", { type: "chars, words" });

let tl = gsap.timeline({ repeat: -1, repeatDelay: 0 });

gsap.from(split.chars, {
  y: 20,
  autoAlpha: 0,
  stagger: 0.02,
});

function animateText(split, currentClass, nextClass) {
  tl.from(split.chars, {
    y: 20,
    autoAlpha: 0,
    stagger: {
      amount: 0.2,
      from: "random",
    },
    duration: 0.5,
  });

  tl.to({}, { duration: 0.4 });

  tl.to(split.chars, {
    y: -20,
    autoAlpha: 0,
    stagger: {
      amount: 0.2,
      from: "random",
    },
    duration: 0.5,
  });

  tl.to({}, { duration: 0.4 });

  tl.set(currentClass, { display: "none" });
  tl.set(nextClass, { display: "inline-block" });
}

animateText(split1, ".span1", ".span2");
animateText(split2, ".span2", ".span3");
animateText(split3, ".span3", ".span1");
