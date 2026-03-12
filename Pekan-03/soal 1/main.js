const preconnect1 = document.createElement("link");
preconnect1.rel = "preconnect";
preconnect1.href = "https://fonts.googleapis.com";

const preconnect2 = document.createElement("link");
preconnect2.rel = "preconnect";
preconnect2.href = "https://fonts.gstatic.com";
preconnect2.crossOrigin = "anonymous";

const fontLink = document.createElement("link");
fontLink.rel = "stylesheet";
fontLink.href =
  "https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap";

document.head.prepend(fontLink);
document.head.prepend(preconnect2);
document.head.prepend(preconnect1);

const backgroundBlob = document.createElement("div");
backgroundBlob.style.position = "fixed";
backgroundBlob.style.top = "0";
backgroundBlob.style.left = "0";
backgroundBlob.style.width = "100%";
backgroundBlob.style.height = "100%";
backgroundBlob.style.zIndex = "-1";
backgroundBlob.style.overflow = "hidden";

backgroundBlob.innerHTML = `<div class="container-blob">
    <div class="blobs" role="presentation">
      <div class="blob-rotate">
        <div class="blob-move">
          <div class="blob"></div>
        </div>
      </div>
      <div class="blob-rotate">
        <div class="blob-move">
          <div class="blob"></div>
        </div>
      </div>
      <div class="blob-rotate">
        <div class="blob-move">
          <div class="blob"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="container-blob">
      <div class="blobs" role="presentation">
        <div class="blob-rotate2">
          <div class="blob-move">
            <div class="blob"></div>
          </div>
        </div>
        <div class="blob-rotate2">
          <div class="blob-move">
            <div class="blob"></div>
          </div>
        </div>
        <div class="blob-rotate2">
          <div class="blob-move">
            <div class="blob"></div>
          </div>
        </div>
      </div>
    </div>`;

document.body.prepend(backgroundBlob);

const styleBackground = document.createElement("style");
styleBackground.textContent = `
  @layer demo {
    :root {
      --blob-size: 600px;
      --blob-speed: 8s;
      --blob-speed-scale: calc(0.75 * var(--blob-speed));
      --blob-speed-move: calc(1 * var(--blob-speed));
      --blob-speed-rotate: calc(2 * var(--blob-speed));
      --blob-opacity: 0.5;
      --blob-blur: 80px;
      --blob-color-1: #ff1af0;
      --blob-color-2: #1af7ff;
      --blob-color-3: #1a75ff;
    }

    .blobs {
      width: 100vw;
      height: auto;
      position: relative;
      filter: blur(var(--blob-blur));
      -webkit-filter: blur(var(--blob-blur));
    }
    .blobs * {
      -webkit-backface-visibility: hidden;
      backface-visibility: hidden;
      transform-origin: 50% 50%;
      transform-style: preserve-3d;
      will-change: transform;
    }
    .blobs:after {
      position: relative;
      display: block;
      width: 22rem;
      height: 38rem;
      background: rgba(184, 184, 184, 0.08);
      -webkit-backdrop-filter: blur(8rem) saturate(1.6);
      backdrop-filter: blur(8rem) saturate(1.6);
      border-radius: 1.5rem;
      z-index: 2;
    }

    .blob {
      width: var(--blob-size);
      height: calc(0.25 * var(--blob-size));
      background-color: var(--blob-color-1);
      border-radius: 100%;
      opacity: var(--blob-opacity);
      mix-blend-mode: multiply;
      -webkit-animation: blob ease-in-out var(--blob-speed-scale) infinite;
      animation: blob ease-in-out var(--blob-speed-scale) infinite;
    }
    .blobs > :nth-child(2) .blob {
      background-color: var(--blob-color-2);
      -webkit-animation-delay: calc(-0.8 * var(--blob-speed-scale));
      animation-delay: calc(-0.8 * var(--blob-speed-scale));
    }
    .blobs > :nth-child(3) .blob {
      background-color: var(--blob-color-3);
      -webkit-animation-delay: calc(-0.2 * var(--blob-speed-scale));
      animation-delay: calc(-0.2 * var(--blob-speed-scale));
    }

    @-webkit-keyframes blob {
      0%, 100% {
        transform: scale(0.8, 2);
      }
      50% {
        transform: scale(1.4, 0.8);
      }
    }

    @keyframes blob {
      0%, 100% {
        transform: scale(0.8, 2);
      }
      50% {
        transform: scale(1.4, 0.8);
      }
    }

    .blob-rotate {
      position: absolute;
      left: 0;
      bottom: 0;
      -webkit-animation: blob-rotate linear var(--blob-speed-rotate) infinite alternate;
      animation: blob-rotate linear var(--blob-speed-rotate) infinite alternate;
    }
    .blob-rotate:nth-child(2) {
      -webkit-animation-duration: calc(2 * var(--blob-speed-rotate));
      animation-duration: calc(2 * var(--blob-speed-rotate));
      -webkit-animation-delay: calc(-1.5 * var(--blob-speed-rotate));
      animation-delay: calc(-1.5 * var(--blob-speed-rotate));
    }
    .blob-rotate:nth-child(3) {
      animation-direction: alternate-reverse;
      -webkit-animation-duration: calc(0.8 * var(--blob-speed-rotate));
      animation-duration: calc(0.8 * var(--blob-speed-rotate));
      -webkit-animation-delay: calc(-1 * var(--blob-speed-rotate));
      animation-delay: calc(-1 * var(--blob-speed-rotate));
    }

    @-webkit-keyframes blob-rotate {
      0% {
        transform: translate3d(-50%, -50%, 0) rotateZ(-28deg);
        transform-origin: 50% 100%;
      }
      100% {
        transform: translate3d(-50%, -50%, 0) rotateZ(28deg);
        transform-origin: 50% 0%;
      }
    }

    @keyframes blob-rotate {
      0% {
        transform: translate3d(-50%, -50%, 0) rotateZ(-28deg);
        transform-origin: 50% 100%;
      }
      100% {
        transform: translate3d(-50%, -50%, 0) rotateZ(28deg);
        transform-origin: 50% 0%;
      }
    }

    .blob-rotate2 {
      position: absolute;
      top: 0;
      right: -20%;
      -webkit-animation: blob-rotate linear var(--blob-speed-rotate) infinite alternate;
      animation: blob-rotate linear var(--blob-speed-rotate) infinite alternate;
    }
    .blob-rotate2:nth-child(2) {
      -webkit-animation-duration: calc(2 * var(--blob-speed-rotate));
      animation-duration: calc(2 * var(--blob-speed-rotate));
      -webkit-animation-delay: calc(-1.5 * var(--blob-speed-rotate));
      animation-delay: calc(-1.5 * var(--blob-speed-rotate));
    }
    .blob-rotate2:nth-child(3) {
      animation-direction: alternate-reverse;
      -webkit-animation-duration: calc(0.8 * var(--blob-speed-rotate));
      animation-duration: calc(0.8 * var(--blob-speed-rotate));
      -webkit-animation-delay: calc(-1 * var(--blob-speed-rotate));
      animation-delay: calc(-1 * var(--blob-speed-rotate));
    }

    @-webkit-keyframes blob-rotate2 {
      0% {
        transform: translate3d(-50%, -50%, 0) rotateZ(-28deg);
        transform-origin: 50% 100%;
      }
      100% {
        transform: translate3d(-50%, -50%, 0) rotateZ(28deg);
        transform-origin: 50% 0%;
      }
    }

    @keyframes blob-rotate2 {
      0% {
        transform: translate3d(-50%, -50%, 0) rotateZ(-28deg);
        transform-origin: 50% 100%;
      }
      100% {
        transform: translate3d(-50%, -50%, 0) rotateZ(28deg);
        transform-origin: 50% 0%;
      }
    }

    .blob-move {
      -webkit-animation: blob-move ease-in-out var(--blob-speed-move) infinite;
      animation: blob-move ease-in-out var(--blob-speed-move) infinite;
    }
    .blobs > :nth-child(2) .blob-move {
      -webkit-animation-delay: calc(-0.8 * var(--blob-speed-move));
      animation-delay: calc(-0.8 * var(--blob-speed-move));
    }
    .blobs > :nth-child(3) .blob-move {
      -webkit-animation-delay: calc(-0.4 * var(--blob-speed-move));
      animation-delay: calc(-0.4 * var(--blob-speed-move));
    }

    @-webkit-keyframes blob-move {
      0%, 100% {
        transform: translateX(30%);
      }
      50% {
        transform: translateX(-30%);
      }
    }

    @keyframes blob-move {
      0%, 100% {
        transform: translateX(30%);
      }
      50% {
        transform: translateX(-30%);
      }
    }
  }

  @layer demo.support {
    *, :before, :after {
      box-sizing: border-box;
    }

    .container-blob {
      position: fixed;
      z-index: -1;
      display: grid;
      min-block-size: 100%;
    }
  }
`;

document.head.appendChild(styleBackground);

const icon = document.createElement("link");
icon.rel = "icon";
icon.href = "/public/images/logo.png";
icon.type = "image/x-icon";
document.head.prepend(icon);
